<?php
/**
 * Created by IntelliJ IDEA.
 * User: antoine
 * Date: 12/08/2018
 * Time: 15:45
 */

namespace App\Controller;

use App\Entity\Champagne;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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

    public function champagneShow($id)
    {
        $product = $this->getDoctrine()
            ->getRepository(Champagne::class)
            ->findOneBy(array('urlLink' => $id));

        if (!$product) {
            throw $this->createNotFoundException(
            );
        }

        return $this->render('view/champagneShow.html.twig', ['champagne' => $product]);
    }

    public function vignoble()
    {
        return $this->render('view/vignoble.html.twig');
    }

    public function boutique(SessionInterface $session)
    {
        $repository = $this->getDoctrine()->getRepository(Champagne::class);
        $champagneListClassique = $repository->findBy(['type' => 'Classique']);
        $champagneListCollection = $repository->findBy(['type' => 'Collection']);
        return $this->render('view/boutique.html.twig',
            [
                'champagneClassique' => $champagneListClassique,
                'champagneCollection' => $champagneListCollection,
            ]);
    }

    public function mentionslegales()
    {
        return $this->render('view/mentionslegales.html.twig');
    }

    public function contact()
    {
        return $this->render('view/contact.html.twig');
    }

    public function commande(SessionInterface $session,Request $request)
    {
        $cart = $session->get('cart');
        $repository = $this->getDoctrine()->getRepository(Champagne::class);
        $champagneListClassique = $repository->findBy(['type' => 'Classique']);
        $champagneListCollection = $repository->findBy(['type' => 'Collection']);
        return $this->render('view/commande.html.twig',
            [
                'champagneClassique' => $champagneListClassique,
                'champagneCollection' => $champagneListCollection,
                'cart' => $cart,
            ]);
    }

    public function showBottles(){
        $repository = $this->getDoctrine()->getRepository(Champagne::class);
        $champagneListClassique = $repository->findBy(['type' => 'Classique']);
        $champagneListCollection = $repository->findBy(['type' => 'Collection']);
        return $this->render('view/showBottles.html.twig',
            [
                'champagneClassique' => $champagneListClassique,
                'champagneCollection' => $champagneListCollection,
            ]);
    }


    public function robots($template = null)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'text/plain');

        return $this->render('robots.txt.twig', array(), $response);
    }
}