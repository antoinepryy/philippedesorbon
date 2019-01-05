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
use App\Service\LanguageManager;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class DefaultController extends Controller
{
    public function index(CartManager $cartManager, LanguageManager $languageManager)
    {
        $cartSize = $cartManager->cartSize();
        return $this->render('view/accueil.html.twig',[
            "cartSize" => $cartSize,
            "lg"=>$languageManager->getLanguageUsingCookie()
        ]);
    }

    public function maison(CartManager $cartManager, LanguageManager $languageManager)
    {
        $cartSize = $cartManager->cartSize();
        return $this->render('view/maison.html.twig', [
            "cartSize" => $cartSize,
            "lg"=>$languageManager->getLanguageUsingCookie()
            ]);
    }

    public function savoirfaire(CartManager $cartManager, LanguageManager $languageManager)
    {
        $cartSize = $cartManager->cartSize();
        return $this->render('view/savoirfaire.html.twig', [
            "cartSize" => $cartSize,
            "lg"=>$languageManager->getLanguageUsingCookie()
        ]);
    }

    public function champagnes(CartManager $cartManager, LanguageManager $languageManager)
    {
        $cartSize = $cartManager->cartSize();
        return $this->render('view/champagnes.html.twig', [
            "cartSize" => $cartSize,
            "lg" => $languageManager->getLanguageUsingCookie()
        ]);
    }

    public function champagneShow($id, CartManager $cartManager, LanguageManager $languageManager)
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
            'cartSize' => $cartSize,
            'lg'=>$languageManager->getLanguageUsingCookie()
        ]);
    }

    public function vignoble(CartManager $cartManager, LanguageManager $languageManager)
    {
        $cartSize = $cartManager->cartSize();
        return $this->render('view/vignoble.html.twig', [
            "cartSize" => $cartSize,
            "lg"=>$languageManager->getLanguageUsingCookie()
        ]);
    }

    public function boutique(CartManager $cartManager, LanguageManager $languageManager)
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
                array_push($champagneWithOption, $availableOptions);
            }
        }
        return $this->render('view/boutique.html.twig',
            [
                'champagneClassique' => $champagneListClassique,
                'champagneCollection' => $champagneListCollection,
                'champagneWithoutOption' => $champagneWithoutOption,
                'champagneWithOption' => $champagneWithOption,
                'cartSize' => $cartSize,
                'lg'=>$languageManager->getLanguageUsingCookie()
            ]);
    }

    public function panier(CartManager $cartManager, LanguageManager $languageManager)
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
                array_push($champagneWithOption, $availableOptions);
            }
        }
        return $this->render('view/panier.html.twig',
            [
                'champagneClassique' => $champagneListClassique,
                'champagneCollection' => $champagneListCollection,
                'champagneWithoutOption' => $champagneWithoutOption,
                'champagneWithOption' => $champagneWithOption,
                'cartSize' => $cartSize,
                'lg'=>$languageManager->getLanguageUsingCookie()
            ]);
    }

    public function mentionslegales(CartManager $cartManager, LanguageManager $languageManager)
    {
        $cartSize = $cartManager->cartSize();
        return $this->render('view/mentionslegales.html.twig', [
            "cartSize" => $cartSize,
            "lg"=>$languageManager->getLanguageUsingCookie()
        ]);
    }

    public function contact(CartManager $cartManager, LanguageManager $languageManager)
    {
        $cartSize = $cartManager->cartSize();
        return $this->render('view/contact.html.twig', [
            "cartSize" => $cartSize,
            "lg"=>$languageManager->getLanguageUsingCookie()
        ]);
    }

    public function cgv(CartManager $cartManager, LanguageManager $languageManager){
        $cartSize = $cartManager->cartSize();
        return $this->render('view/cgv.html.twig', [
            "cartSize" => $cartSize,
            "lg"=>$languageManager->getLanguageUsingCookie()
        ]);
    }


    public function showBottles(CartManager $cartManager, LanguageManager $languageManager)
    {
        $cartSize = $cartManager->cartSize();
        $repository = $this->getDoctrine()->getRepository(Champagne::class);
        $champagneListClassique = $repository->findBy(['type' => 'Classique']);
        $champagneListCollection = $repository->findBy(['type' => 'Collection']);
        return $this->render('view/showBottles.html.twig',
            [
                'champagneClassique' => $champagneListClassique,
                'champagneCollection' => $champagneListCollection,
                'cartSize' => $cartSize,
                'lg' => $languageManager->getLanguageUsingCookie()
            ]);
    }

    public function foodGrid()
    {
        return $this->render('view/foodGrid.html.twig');
    }

    public function gastronomie($plat, CartManager $cartManager, LanguageManager $languageManager)
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
            'cartSize' => $cartSize,
            'lg' => $languageManager->getLanguageUsingCookie()
        ]);
    }


}
