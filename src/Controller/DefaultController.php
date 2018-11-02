<?php
/**
 * Created by IntelliJ IDEA.
 * User: antoine
 * Date: 12/08/2018
 * Time: 15:45
 */

namespace App\Controller;

use App\Entity\Champagne;
use App\Entity\ChampagneOption;
use App\Service\CartManager;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class DefaultController extends Controller
{
    public function index(SessionInterface $session, CartManager $cartManager)
    {
        $cartSize = $cartManager->cartSize();
        return $this->render('view/accueil.html.twig',
            ["cartSize" => $cartSize]);
    }

    public function maison(SessionInterface $session, CartManager $cartManager)
    {
        $cartSize = $cartManager->cartSize();
        return $this->render('view/maison.html.twig',
            ["cartSize" => $cartSize]);
    }

    public function savoirfaire(SessionInterface $session, CartManager $cartManager)
    {
        $cartSize = $cartManager->cartSize();
        return $this->render('view/savoirfaire.html.twig',
            ["cartSize" => $cartSize]);
    }

    public function champagnes(SessionInterface $session, CartManager $cartManager)
    {
        $cartSize = $cartManager->cartSize();
        return $this->render('view/champagnes.html.twig',
            ["cartSize" => $cartSize]);
    }

    public function champagneShow($id, SessionInterface $session, CartManager $cartManager)
    {
        $cartSize = $cartManager->cartSize();
        $product = $this->getDoctrine()
            ->getRepository(Champagne::class)
            ->findOneBy(array('urlLink' => $id));
        if (!$product) {
            throw $this->createNotFoundException();
        }
        return $this->render('view/champagneShow.html.twig', [
            'champagne' => $product,
            'cartSize' => $cartSize
        ]);
    }

    public function vignoble(SessionInterface $session, CartManager $cartManager)
    {
        $cartSize = $cartManager->cartSize();
        return $this->render('view/vignoble.html.twig',
            ["cartSize" => $cartSize]);
    }

    public function boutique(SessionInterface $session, CartManager $cartManager)
    {
        $cartSize = $cartManager->cartSize();
        $repository = $this->getDoctrine()->getRepository(Champagne::class);
        $champagneListClassique = $repository->findBy(['type' => 'Classique']);
        $champagneListCollection = $repository->findBy(['type' => 'Collection']);
        $allChampagne = $repository->findAll();
        $champagneWithoutOption = [];
        $champagneWithOption = [];
        foreach ($allChampagne as $champagne){
            if (count($champagne->getChampagneOptions())==0){
                array_push($champagneWithoutOption, $champagne);
            }
            else{
                $availableOptions = $champagne->getChampagneOptions();
                array_push($champagneWithOption, [$champagne->getphotoBouteille(), $availableOptions]);
            }
        }
        return $this->render('view/boutique.html.twig',
            [
                'champagneClassique' => $champagneListClassique,
                'champagneCollection' => $champagneListCollection,
                'champagneWithoutOption' => $champagneWithoutOption,
                'champagneWithOption' => $champagneWithOption,
                'cartSize' => $cartSize
            ]);
    }

    public function panier(SessionInterface $session, CartManager $cartManager)
    {
        $cartSize = $cartManager->cartSize();
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

    public function mentionslegales(SessionInterface $session, CartManager $cartManager)
    {
        $cartSize = $cartManager->cartSize();
        return $this->render('view/mentionslegales.html.twig',
            ["cartSize" => $cartSize]);
    }

    public function contact(SessionInterface $session, CartManager $cartManager)
    {
        $cartSize = $cartManager->cartSize();
        return $this->render('view/contact.html.twig',
            ["cartSize" => $cartSize]);
    }

    public function commande(SessionInterface $session, Request $request, CartManager $cartManager)
    {
        $cart = $session->get('cart');
        $cartSize = $cartManager->cartSize();
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


    public function showBottles(SessionInterface $session, CartManager $cartManager)
    {
        $cartSize = $cartManager->cartSize();
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

    public function foodGrid()
    {
        return $this->render('view/foodGrid.html.twig');
    }

    public function gastronomie($plat, SessionInterface $session, CartManager $cartManager)
    {
        $cartSize = $cartManager->cartSize();
        switch ($plat) {
            case 'Charlotte':
                $food = "charlotte";
                break;
            case 'CoquilleSaintJacques':
                $food = "coquilleSaintJacques";
                break;
            case 'Soupe':
                $food = "soupe";
                break;
            case 'Veloute':
                $food = "langoustines";
                break;
            case 'Risotto':
                $food = "risotto";
                break;
            case 'Huitres':
                $food = "huitres";
                break;
            case 'Lotte':
                $food = "lotte";
                break;
            case 'Poireaux':
                $food = "poireaux";
                break;
            case 'Granite':
                $food = "granite";
                break;
        }
        return $this->render('view/food/' . $food . '.html.twig', [
            'cartSize' => $cartSize
        ]);
    }


}