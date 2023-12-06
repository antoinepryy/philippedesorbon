<?php
/**
 * Created by IntelliJ IDEA.
 * User: antoi
 * Date: 18/10/2018
 * Time: 13:00
 */

namespace App\Controller;

use App\Service\CartManager;
use App\Service\LanguageManager;
use DOMDocument;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


class CRCAController extends Controller
{

    /**
     * @Route("/PaiementEnLigne", name="online_payment")
     */
    public function sendRequest(CartManager $cartManager, SessionInterface $session, LanguageManager $languageManager){
        $user = $this->getUser();
        $idOrderCRCA = $session->get('idOrderCRCA');
        $priceOrderCRCA = $session->get('priceOrderCRCA');
        $cartSize = $cartManager->cartSize();
        // Ennonciation de variables
        $pbx_site = '2039805';
        $pbx_rang = '01';
        $pbx_identifiant = '933494528';
        $pbx_cmd = $idOrderCRCA;								//variable de test cmd_test1
        $pbx_porteur = $user->getEmail();							//variable de test test@test.fr
        $pbx_total = $priceOrderCRCA;									//variable de test 100
        // Suppression des points ou virgules dans le montant
        $pbx_total = str_replace(",", "", $pbx_total);
        $pbx_total = str_replace(".", "", $pbx_total);

        // Paramétrage des urls de redirection apr�s paiement
        $pbx_effectue = 'https://www.champagne-philippedesorbon.com/CommandeValidee/CRCA';
        $pbx_annule = 'https://www.champagne-philippedesorbon.com/Checkout';
        $pbx_refuse = 'https://www.champagne-philippedesorbon.com/Checkout';
        // Paramétrage de l'url de retour back office site
        $pbx_repondre_a = 'https://www.champagne-philippedesorbon.com/';
        // Param�trage du retour back office site
        $pbx_retour = 'Mt:M;Ref:R;Auto:A;Erreur:E';

        $xml = new \SimpleXMLElement('<shoppingcart/>');
        $total = $xml->addChild('total');
        $cartSizeInt = (int)$cartSize;

        $total->addChild('totalQuantity', $cartSizeInt);
        $pbx_shoppingCart = $xml->asXML();
        $pbx_shoppingCart = str_replace("<?xml version=\"1.0\"?>", "", $pbx_shoppingCart);


        $billingXml = new \SimpleXMLElement('<Billing/>');
        $address = $billingXml->addChild('Address');
        $address->addChild('FirstName', htmlspecialchars($user->getFirstName()));
        $address->addChild('LastName', htmlspecialchars($user->getLastName()));
        $address->addChild('Address1', htmlspecialchars($user->getAddressStreet()));
        $address->addChild('ZipCode', htmlspecialchars($user->getAddressZipCode()));
        $address->addChild('City', htmlspecialchars($user->getAddressCity()));
        $address->addChild('CountryCode', htmlspecialchars("250"));

        // Convert XML object to a string
        $pbx_billing = $billingXml->asXML();
        $pbx_billing = str_replace("<?xml version=\"1.0\"?>", "", $pbx_billing);


        //test
        //$keyTest = '30261B89F2A7B6E721B7FD45F2BE668E3782388FC8518B6763D7562B42ED552F860B03800F9529B9F099DB71FD46516783EDBBDA7932BF5129E9266067D0BE14';
        //prod
        $keyTest = '293F885EE28F52E31BB7EE6E7DC0FC607BC03E362087C6DFE0D0F8E8CC4FC9751B4A8F10DC9160B5E7B42E52CE5B343B53FBC06C8B407D602222AD7A7E5EA2AF';


// --------------- TESTS DE DISPONIBILITE DES SERVEURS ---------------

        $serveurs = array('tpeweb.paybox.com', //serveur primaire
            'tpeweb1.paybox.com'); //serveur secondaire
        $serveurOK = "";
        //phpinfo(); <== voir paybox
        foreach($serveurs as $serveur){
            $doc = new DOMDocument();
            $doc->loadHTMLFile('https://'.$serveur.'/load.html');
            $server_status = "";
            $element = $doc->getElementById('server_status');
            if($element){
                $server_status = $element->textContent;}
            if($server_status == "OK"){
                $serveurOK = $serveur;
                break;}
        }

        if(!$serveurOK){
            die("Erreur : Aucun serveur n'a �t� trouv�");}
        // Activation de l'univers de pr�production
        //$serveurOK = 'preprod-tpeweb.paybox.com';

        //Cr�ation de l'url cgi paybox
        $serveurOK = 'https://'.$serveurOK.'/cgi/MYchoix_pagepaiement.cgi';
        // echo $serveurOK;



// --------------- TRAITEMENT DES VARIABLES ---------------

        $dateTime = date("c");

        $msg = "PBX_SITE=".$pbx_site.
            "&PBX_RANG=".$pbx_rang.
            "&PBX_IDENTIFIANT=".$pbx_identifiant.
            "&PBX_TOTAL=".$pbx_total.
            "&PBX_DEVISE=978".
            "&PBX_CMD=".$pbx_cmd.
            "&PBX_PORTEUR=".$pbx_porteur.
            "&PBX_REPONDRE_A=".$pbx_repondre_a.
            "&PBX_RETOUR=".$pbx_retour.
            "&PBX_EFFECTUE=".$pbx_effectue.
            "&PBX_ANNULE=".$pbx_annule.
            "&PBX_REFUSE=".$pbx_refuse.
            "&PBX_HASH=SHA512".
            "&PBX_SHOPPINGCART=".urlencode($pbx_shoppingCart).
            "&PBX_BILLING=".urlencode($pbx_billing).
            "&PBX_TIME=".$dateTime;
        //echo $msg;

        $binKey = pack("H*", $keyTest);
        $hmac = strtoupper(hash_hmac('sha512', $msg, $binKey));

        return $this->render('CRCA/onlinePayment.html.twig',[
            'serveurOK' => $serveurOK,
            'pbx_site' => $pbx_site,
            'pbx_rang' => $pbx_rang,
            'pbx_identifiant' => $pbx_identifiant,
            'pbx_cmd' => $pbx_cmd,
            'pbx_porteur' => $pbx_porteur,
            'pbx_total' => $pbx_total,
            'pbx_devise' => '978',
            'pbx_effectue' => $pbx_effectue,
            'pbx_annule' => $pbx_annule,
            'pbx_repondre_a' => $pbx_repondre_a,
            'pbx_refuse' => $pbx_refuse,
            'pbx_retour' => $pbx_retour,
            'dateTime' => $dateTime,
            'pbx_hmac' => $hmac,
            'pbx_shopping_cart' => $pbx_shoppingCart,
            'pbx_billing' => $pbx_billing,
            'cartSize' => $cartSize,
            'lg' => $languageManager->getLanguageUsingCookie()

        ]);

    }




}
