<?php
/**
 * Created by IntelliJ IDEA.
 * User: antoi
 * Date: 18/10/2018
 * Time: 13:00
 */

namespace App\Controller;

use DOMDocument;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


class CRCAController extends Controller
{

    /**
     * @Route("/OnlinePayment", name="online_payment")
     */
    public function sendRequest(){

        // Ennonciation de variables
        $pbx_site = '2039805';
        $pbx_rang = '001';
        $pbx_identifiant = '933494528';
        $pbx_cmd = 'cmd_test1';								//variable de test cmd_test1
        $pbx_porteur = 'test@test.fr';							//variable de test test@test.fr
        $pbx_total = '100';									//variable de test 100
        // Suppression des points ou virgules dans le montant
        $pbx_total = str_replace(",", "", $pbx_total);
        $pbx_total = str_replace(".", "", $pbx_total);

        // Param�trage des urls de redirection apr�s paiement
        $pbx_effectue = 'http://www.votre-site.extention/page-de-confirmation';
        $pbx_annule = 'http://www.votre-site.extention/page-d-annulation';
        $pbx_refuse = 'http://www.votre-site.extention/page-de-refus';
        // Param�trage de l'url de retour back office site
        $pbx_repondre_a = 'http://www.votre-site.extention/page-de-back-office-site';
        // Param�trage du retour back office site
        $pbx_retour = 'Mt:M;Ref:R;Auto:A;Erreur:E';

        // Connection � la base de donn�es
        // mysql_connect...
        // On r�cup�re la cl� secr�te HMAC (stock�e dans une base de donn�es par exemple) et que l�on renseigne dans la variable $keyTest;
        //$keyTest = '0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF';
        $keyTest = 'CA6B888E44130C8ABD6584D6D539E01C70C845669BCFE027B79877C506C9E9369A7A352ECF64554DAC485FFF2A322868DF70307053C17E5AB8999D20CBCE8014';



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
            // Le serveur est pr�t et les services op�rationnels
                $serveurOK = $serveur;
                break;}
            // else : La machine est disponible mais les services ne le sont pas.
        }

        //curl_close($ch); <== voir paybox
        if(!$serveurOK){
            die("Erreur : Aucun serveur n'a �t� trouv�");}
        // Activation de l'univers de pr�production
        //$serveurOK = 'preprod-tpeweb.paybox.com';

        //Cr�ation de l'url cgi paybox
        $serveurOK = 'https://'.$serveurOK.'/cgi/MYchoix_pagepaiement.cgi';
        // echo $serveurOK;



// --------------- TRAITEMENT DES VARIABLES ---------------

        // On r�cup�re la date au format ISO-8601
        $dateTime = date("c");

        // On cr�e la cha�ne � hacher sans URLencodage
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
            "&PBX_TIME=".$dateTime;
        // echo $msg;

        // Si la cl� est en ASCII, On la transforme en binaire
        $binKey = pack("H*", $keyTest);

        // On calcule l�empreinte (� renseigner dans le param�tre PBX_HMAC) gr�ce � la fonction hash_hmac et //
        // la cl� binaire
        // On envoi via la variable PBX_HASH l'algorithme de hachage qui a �t� utilis� (SHA512 dans ce cas)
        // Pour afficher la liste des algorithmes disponibles sur votre environnement, d�commentez la ligne //
        // suivante
        // print_r(hash_algos());
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
            'cartSize' => 0

        ]);

    }

}