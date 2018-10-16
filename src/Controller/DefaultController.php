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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DefaultController extends Controller
{
    public function index(SessionInterface $session)
    {
        if ($session->has('cart')){
            $cartSize = count($session->get('cart'));
        }
        else{
            $cartSize = 0;
        }
        return $this->render('view/accueil.html.twig',
            ["cartSize" => $cartSize]);
    }

    public function maison(SessionInterface $session)
    {
        if ($session->has('cart')){
            $cartSize = count($session->get('cart'));
        }
        else{
            $cartSize = 0;
        }
        return $this->render('view/maison.html.twig',
            ["cartSize" => $cartSize]);
    }

    public function savoirfaire(SessionInterface $session)
    {
        if ($session->has('cart')){
            $cartSize = count($session->get('cart'));
        }
        else{
            $cartSize = 0;
        }
        return $this->render('view/savoirfaire.html.twig',
            ["cartSize" => $cartSize]);
    }

    public function champagnes(SessionInterface $session)
    {
        if ($session->has('cart')){
            $cartSize = count($session->get('cart'));
        }
        else{
            $cartSize = 0;
        }
        return $this->render('view/champagnes.html.twig',
            ["cartSize" => $cartSize]);
    }

    public function champagneShow($id, SessionInterface $session)
    {
        if ($session->has('cart')){
            $cartSize = count($session->get('cart'));
        }
        else{
            $cartSize = 0;
        }
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
        if ($session->has('cart')){
            $cartSize = count($session->get('cart'));
        }
        else{
            $cartSize = 0;
        }
        return $this->render('view/vignoble.html.twig',
            ["cartSize" => $cartSize]);
    }

    public function boutique(SessionInterface $session)
    {
        if ($session->has('cart')){
            $cartSize = count($session->get('cart'));
        }
        else{
            $cartSize = 0;
        }
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
        if ($session->has('cart')){
            $cartSize = count($session->get('cart'));
        }
        else{
            $cartSize = 0;
        }
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
        if ($session->has('cart')){
            $cartSize = count($session->get('cart'));
        }
        else{
            $cartSize = 0;
        }
        return $this->render('view/mentionslegales.html.twig',
            ["cartSize" => $cartSize]);
    }

    public function contact( SessionInterface $session)
    {
        if ($session->has('cart')){
            $cartSize = count($session->get('cart'));
        }
        else{
            $cartSize = 0;
        }
        return $this->render('view/contact.html.twig',
            ["cartSize" => $cartSize]);
    }

    public function commande(SessionInterface $session,Request $request)
    {
        $cart = $session->get('cart');
        if ($session->has('cart')){
            $cartSize = count($session->get('cart'));
        }
        else{
            $cartSize = 0;
        }
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

    public function account(SessionInterface $session,Request $request)
    {
        $cart = $session->get('cart');
        if ($session->has('cart')){
            $cartSize = count($session->get('cart'));
        }
        else{
            $cartSize = 0;
        }
        if( $this->getUser()){
            $user = $this->getUser();
            return $this->render('view/compte.html.twig',
                [
                    'cartSize' => $cartSize,
                    'user' => $user
                ]);
        }
        else{
            return $this->redirectToRoute('login');
        }


    }

    public function showBottles(SessionInterface $session){
        if ($session->has('cart')){
            $cartSize = count($session->get('cart'));
        }
        else{
            $cartSize = 0;
        }
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

    public function foodGrid(){
    return $this->render('view/foodGrid.html.twig');
    }

    public function gastronomie($plat, SessionInterface $session){
        if ($session->has('cart')){
            $cartSize = count($session->get('cart'));
        }
        else{
            $cartSize = 0;
        }

        switch ($plat){
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
        return $this->render('view/food/'.$food.'.html.twig',[
            'cartSize' => $cartSize
        ]);
    }

    public function orderValidated(\Swift_Mailer $mailer, SessionInterface $session){
        $champagneRepository = $this->getDoctrine()->getRepository(Champagne::class);
        $optionRepository = $this->getDoctrine()->getRepository(ChampagneOption::class);
        //$champagneListClassique = $repository->findBy(['type' => 'Classique']);
        $cart = $session->get('cart');
        $user = $this->getUser();
        $orderContent = [];
        $orderPrice = 0;

        foreach ($cart as $champagne){
            if (count($champagne) === 3){
                $champagneModel = $champagneRepository->findOneBy(['id'=>$champagne[0]]);
                $champagneOption = $optionRepository->findOneBy(['id'=>$champagne[2]]);
                $champagneQuantity = $champagne[1];
                array_push($orderContent,$champagneQuantity.' x '.$champagneModel->getName().' '.$champagneOption->getName());
                $orderPrice += $champagneOption->getPrice()*$champagneQuantity;
            }
            else {
                $champagneQuantity = $champagne[1];
                $champagneModel = $champagneRepository->findOneBy(['id'=>$champagne[0]]);
                array_push($orderContent, $champagne[1].' x '.$champagneModel->getName());
                $orderPrice += $champagneModel->getPrice()*$champagneQuantity;
            }
        }

        $messageClient = (new \Swift_Message('Philippe de Sorbon'))
            ->setFrom('antoine.ap.57@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                    'emails/orderClient.html.twig', [
                        'orderContent' => $orderContent,
                        'totalPrice' => $orderPrice
                    ]
                ),
                'text/html'
            );

        $messageAdmin = (new \Swift_Message('Philippe de Sorbon'))
            ->setFrom('antoine.ap.57@gmail.com')
            ->setTo('antoine.ap.57@gmail.com')
            ->setBody(
                $this->renderView(
                    'emails/orderAdmin.html.twig', [
                        'orderContent' => $orderContent,
                        'totalPrice' => $orderPrice,
                        'clientEmail' => $user->getEmail()
                    ]
                ),
                'text/html'
            );

        $mailer->send($messageClient);
        $mailer->send($messageAdmin);
        $session->set('cart',[]);

        return $this->render('view/validOrder.html.twig',[
            'cartSize' => 0
        ]);
    }

    public function checkout(){
        $user = $this->getUser();
        if ($user === null){
            return $this->redirectToRoute('login');
        }
        else{
            return $this->redirectToRoute('order_validated');
        }
    }


    public function robots($template = null)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'text/plain');

        return $this->render('robots.txt.twig', array(), $response);
    }
}