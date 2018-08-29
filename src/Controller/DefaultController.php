<?php
/**
 * Created by IntelliJ IDEA.
 * User: antoine
 * Date: 12/08/2018
 * Time: 15:45
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function index()
    {
        return $this->render('view/accueil.html.twig');
    }

    public function maison()
    {
        return $this->render('view/maison.html.twig');
    }

    public function savoirfaire()
    {
        return $this->render('view/savoirfaire.html.twig');
    }

    public function champagnes()
    {
        return $this->render('view/champagnes.html.twig');
    }

    public function vignoble()
    {
        return $this->render('view/vignoble.html.twig');
    }

    public function boutique()
    {
        return $this->render('view/boutique.html.twig');
    }

    public function mentionslegales()
    {
        return $this->render('view/mentionslegales.html.twig');
    }

    public function contact()
    {
        return $this->render('view/contact.html.twig');
    }
}