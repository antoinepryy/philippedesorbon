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
    public function index(SessionInterface $session)
    {
        $cartSize = count($session->get('cart'));
        return $this->render('view/accueil.html.twig',
            ["cartSize" => $cartSize]);
    }

    public function maison(SessionInterface $session)
    {
        $cartSize = count($session->get('cart'));
        return $this->render('view/maison.html.twig',
            ["cartSize" => $cartSize]);
    }

    public function savoirfaire(SessionInterface $session)
    {
        $cartSize = count($session->get('cart'));
        return $this->render('view/savoirfaire.html.twig',
            ["cartSize" => $cartSize]);
    }

    public function champagnes(SessionInterface $session)
    {
        $cartSize = count($session->get('cart'));
        return $this->render('view/champagnes.html.twig',
            ["cartSize" => $cartSize]);
    }

    public function champagneShow($id, SessionInterface $session)
    {
        $cartSize = count($session->get('cart'));
        $product = $this->getDoctrine()
            ->getRepository(Champagne::class)
            ->findOneBy(array('urlLink' => $id));
        if (!$product) {
            throw $this->createNotFoundException(
            );
        }
        return $this->render('view/champagneShow.html.twig', [
            'champagne' => $product,
            'cartSize' => $cartSize
            ]);
    }

    public function vignoble(SessionInterface $session)
    {
        $cartSize = count($session->get('cart'));
        return $this->render('view/vignoble.html.twig',
            ["cartSize" => $cartSize]);
    }

    public function boutique(SessionInterface $session)
    {
        $cartSize = count($session->get('cart'));
        $repository = $this->getDoctrine()->getRepository(Champagne::class);
        $champagneListClassique = $repository->findBy(['type' => 'Classique']);
        $champagneListCollection = $repository->findBy(['type' => 'Collection']);
        return $this->render('view/boutique.html.twig',
            [
                'champagneClassique' => $champagneListClassique,
                'champagneCollection' => $champagneListCollection,
                'cartSize' => $cartSize
            ]);
    }

    public function panier(SessionInterface $session)
    {
        $cartSize = count($session->get('cart'));
        $repository = $this->getDoctrine()->getRepository(Champagne::class);
        $champagneListClassique = $repository->findBy(['type' => 'Classique']);
        $champagneListCollection = $repository->findBy(['type' => 'Collection']);
        return $this->render('view/panier.html.twig',
            [
                'champagneClassique' => $champagneListClassique,
                'champagneCollection' => $champagneListCollection,
                'cartSize' => $cartSize
            ]);
    }

    public function mentionslegales(SessionInterface $session)
    {
        $cartSize = count($session->get('cart'));
        return $this->render('view/mentionslegales.html.twig',
            ["cartSize" => $cartSize]);
    }

    public function contact( SessionInterface $session)
    {
        $cartSize = count($session->get('cart'));
        return $this->render('view/contact.html.twig',
            ["cartSize" => $cartSize]);
    }

    public function commande(SessionInterface $session,Request $request)
    {
        $cart = $session->get('cart');
        $cartSize = count($session->get('cart'));
        $repository = $this->getDoctrine()->getRepository(Champagne::class);
        $champagneListClassique = $repository->findBy(['type' => 'Classique']);
        $champagneListCollection = $repository->findBy(['type' => 'Collection']);
        return $this->render('view/commande.html.twig',
            [
                'champagneClassique' => $champagneListClassique,
                'champagneCollection' => $champagneListCollection,
                'cart' => $cart,
                'cartSize' => $cartSize
            ]);
    }

    public function showBottles(SessionInterface $session){
        $cartSize = count($session->get('cart'));
        $repository = $this->getDoctrine()->getRepository(Champagne::class);
        $champagneListClassique = $repository->findBy(['type' => 'Classique']);
        $champagneListCollection = $repository->findBy(['type' => 'Collection']);
        return $this->render('view/showBottles.html.twig',
            [
                'champagneClassique' => $champagneListClassique,
                'champagneCollection' => $champagneListCollection,
                'cartSize' => $cartSize
            ]);
    }


    public function robots($template = null)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'text/plain');

        return $this->render('robots.txt.twig', array(), $response);
    }
}