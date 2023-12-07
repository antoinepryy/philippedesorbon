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
        $pbx_souhaitauthent = '01';
        // Paramétrage des urls de redirection apr�s paiement
        $pbx_effectue = 'https://www.champagne-philippedesorbon.com/CommandeValidee/CRCA';
        $pbx_annule = 'https://www.champagne-philippedesorbon.com/Checkout';
        $pbx_refuse = 'https://www.champagne-philippedesorbon.com/Checkout';
        // Paramétrage de l'url de retour back office site
        $pbx_repondre_a = 'https://www.champagne-philippedesorbon.com/';
        // Param�trage du retour back office site
        $pbx_retour = 'Mt:M;Ref:R;Auto:A;Erreur:E';


        $cartSizeInt = (int)$cartSize;




        $pbx_shoppingcart = "<?xml version=\"1.0\" encoding=\"utf-8\"?><shoppingcart><total><totalQuantity>".$cartSizeInt."</totalQuantity></total></shoppingcart>";


        $pbx_prenom_fact = $user->getFirstName();							//variable de test Jean-Marie
        $pbx_nom_fact = $user->getLastName();									//variable de test Thomson
        $pbx_adresse1_fact = $user->getAddressStreet();								//variable de test 1 rue de Paris
        $pbx_zipcode_fact = $user->getAddressZipCode();							//variable de test 75001
        $pbx_city_fact = $user->getAddressCity();									//variable de test Paris
        $pbx_country_fact = 250;		//variable de test 250 (pour la France)
        $pbx_billing = "<?xml version=\"1.0\" encoding=\"utf-8\"?><Billing><Address><FirstName>".$pbx_prenom_fact."</FirstName>".
            "<LastName>".$pbx_nom_fact."</LastName><Address1>".$pbx_adresse1_fact."</Address1><ZipCode>".$pbx_zipcode_fact."</ZipCode>".
            "<City>".$pbx_city_fact."</City><CountryCode>".$pbx_country_fact."</CountryCode>".
            "</Address></Billing>";

        $maxLength = 22;		// Longueur maximale de FirstName et LastName
        $maxLength2 = 50;		// Longueur maximale des champs Address1, Address2 et City
        $pbx_prenom_fact = remove_accents($pbx_prenom_fact);	// Suppression des accents
        $pbx_nom_fact = remove_accents($pbx_nom_fact);
        $pbx_adresse1_fact = remove_accents($pbx_adresse1_fact);
        $pbx_city_fact = remove_accents($pbx_city_fact);

        $pbx_prenom_fact = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $pbx_prenom_fact);	// Suppression des espaces inutiles
        $pbx_nom_fact = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $pbx_nom_fact);
        $pbx_adresse1_fact = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $pbx_adresse1_fact);
        $pbx_city_fact = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $pbx_city_fact);
        $pbx_country_fact = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $pbx_country_fact);

        if (!empty($maxLength) && is_numeric($maxLength) && $maxLength > 0) {		// Vérification de la longueur maximale des données
            if (function_exists('mb_strlen')) {
                $pbx_prenom_fact = mb_substr($pbx_prenom_fact, 0, $maxLength);
            }
            elseif (strlen($pbx_prenom_fact) > $maxLength) {
                $pbx_prenom_fact = substr($pbx_prenom_fact, 0, $maxLength);
            }
        }
        if (!empty($maxLength) && is_numeric($maxLength) && $maxLength > 0) {
            if (function_exists('mb_strlen')) {
                $pbx_nom_fact = mb_substr($pbx_nom_fact, 0, $maxLength);
            }
            elseif (strlen($pbx_prenom_fact) > $maxLength) {
                $pbx_nom_fact = substr($pbx_nom_fact, 0, $maxLength);
            }
        }
        if (!empty($maxLength2) && is_numeric($maxLength2) && $maxLength2 > 0) {
            if (function_exists('mb_strlen')) {
                $pbx_adresse1_fact = mb_substr($pbx_adresse1_fact, 0, $maxLength2);
            }
            elseif (strlen($pbx_adresse1_fact) > $maxLength2) {
                $pbx_adresse1_fact = substr($pbx_adresse1_fact, 0, $maxLength2);
            }
        }
        if (!empty($maxLength2) && is_numeric($maxLength2) && $maxLength2 > 0) {
            if (function_exists('mb_strlen')) {
                $pbx_city_fact = mb_substr($pbx_city_fact, 0, $maxLength2);
            }
            elseif (strlen($pbx_city_fact) > $maxLength2) {
                $pbx_city_fact = substr($pbx_city_fact, 0, $maxLength2);
            }
        }

        $pbx_prenom_fact = strtoupper($pbx_prenom_fact);
        $pbx_nom_fact = strtoupper($pbx_nom_fact);
        $pbx_adresse1_fact = strtoupper($pbx_adresse1_fact);
        $pbx_city_fact = strtoupper($pbx_city_fact);
        $pbx_country_fact = strtoupper($pbx_country_fact);

        function remove_accents($string) {
            if ( !preg_match('/[\x80-\xff]/', $string) )
                return $string;

            $chars = array(
                // Decompositions for Latin-1 Supplement
                chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
                chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
                chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
                chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
                chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
                chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
                chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
                chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
                chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
                chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
                chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
                chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
                chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
                chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
                chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
                chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
                chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
                chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
                chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
                chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
                chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
                chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
                chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
                chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
                chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
                chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
                chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
                chr(195).chr(191) => 'y',
                // Decompositions for Latin Extended-A
                chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
                chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
                chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
                chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
                chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
                chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
                chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
                chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
                chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
                chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
                chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
                chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
                chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
                chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
                chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
                chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
                chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
                chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
                chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
                chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
                chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
                chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
                chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
                chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
                chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
                chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
                chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
                chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
                chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
                chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
                chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
                chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
                chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
                chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
                chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
                chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
                chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
                chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
                chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
                chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
                chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
                chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
                chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
                chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
                chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
                chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
                chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
                chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
                chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
                chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
                chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
                chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
                chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
                chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
                chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
                chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
                chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
                chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
                chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
                chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
                chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
                chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
                chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
                chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
            );

            $string = strtr($string, $chars);

            return $string;
        }



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
            "&PBX_TIME=".$dateTime.
            "&PBX_SHOPPINGCART=".$pbx_shoppingcart.
            "&PBX_BILLING=".$pbx_billing.
            "&PBX_SOUHAITAUTHENT=".$pbx_souhaitauthent;
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
            'pbx_shopping_cart' => $pbx_shoppingcart,
            'pbx_billing' => $pbx_billing,
            'cartSize' => $cartSize,
            'pbx_souhaitauthent' => $pbx_souhaitauthent,
            'lg' => $languageManager->getLanguageUsingCookie()

        ]);

    }




}
