<?php
/**
 * Created by IntelliJ IDEA.
 * User: antoine
 * Date: 19/09/2018
 * Time: 00:00
 */

namespace App\Controller;

use function MongoDB\BSON\toJSON;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ShopController extends Controller
{
    /**
     * @Route("/AddProduct", name="add_product")
     */
    public function addProduct(SessionInterface $session,Request $request){
        if ($request->isXmlHttpRequest()) {
            $cart = $session->get('cart');
            $bottleId = $request->query->get('bottleId');
            if (is_null($cart)){
                $cart = [];
            }
            for ($i=0 ; $i < count($cart); $i++) {
                if($cart[$i][0]==$bottleId){
                    $cart[$i][1]++;
                    break;
                };
            }
            if ($i==count($cart)){
                array_push($cart, [intval($bottleId), 1]);
            }
            $session->set('cart',$cart);
            return  new JsonResponse($cart);
        }
        return new JsonResponse('no results found', Response::HTTP_NOT_FOUND);

    }

    /**
     * @Route("/RemoveOneProduct", name="remove_one_product")
     */
    public function removeOneProduct(SessionInterface $session, $id){
        $foobar = $session->get('test');
        die(var_dump($foobar));
        return $this->render('view/accueil.html.twig');

    }
    /**
     * @Route("/RemoveAllProducts", name="remove_all_products")
     */
    public function removeAllProduct(){

    }

    /**
     * @Route("/ClearCart", name="clear_cart")
     */
    public function clearCart(SessionInterface $session){
        $session->set('cart',[]);
    }

    /**
     * @Route("/seeCart", name="see_cart")
     */
    public function seeCart(SessionInterface $session){
        $cart = $session->get('cart');
        die(var_dump($cart));
    }

    /**
     * @Route("/test", name="test")
     */
    public function test(SessionInterface $session){

        $cart = $session->get('cart');
        die(is_null($cart));
    }


}