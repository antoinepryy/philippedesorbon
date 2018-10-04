<?php
/**
 * Created by IntelliJ IDEA.
 * User: antoine
 * Date: 19/09/2018
 * Time: 00:00
 */

namespace App\Controller;

use App\Entity\Champagne;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ShopController extends Controller
{

    /**
     * @Route("/GetCart", name="get_cart")
     */
    public function getCart(SessionInterface $session,Request $request){
        if ($request->isXmlHttpRequest()) {
            $cart = $session->get('cart');
            return  new JsonResponse($cart);
        }
        return new JsonResponse('no results found', Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route("/PreOrder", name="pre_order")
     */
    public function preOrder(SessionInterface $session,Request $request){
        $cart = $session->get('cart');
        if (is_null($cart)){
            $cart = [];
            $session->set('cart',$cart);
        }
        $bottleId = $request->query->get('bottleId');
        $repository = $this->getDoctrine()->getRepository(Champagne::class);
        $champagne = $repository->find($bottleId);
        $hasOptions = $champagne->hasOptions();
        if ($hasOptions){

            $options = $champagne->getOptions();
            $optionsTab = [];
            for ($i=0 ; $i < count($options); $i++) {
                array_push($optionsTab, [$options[$i]->getId(),$options[$i]->getName(), $options[$i]->getPrice()]);
            }
            return  new JsonResponse([true, $optionsTab]);
        }
        else{
            return  new JsonResponse([false]);
        }


    }

    /**
     * @Route("/AddProduct", name="add_product")
     */
    public function addProduct(SessionInterface $session,Request $request){
        if ($request->isXmlHttpRequest()) {
            $isHidden = false;
            $quantity = 0;
            $cart = $session->get('cart');
            $bottleId = $request->query->get('bottleId');
            for ($i=0 ; $i < count($cart); $i++) {
                if($cart[$i][0]==$bottleId){
                    $cart[$i][1] = $cart[$i][1] + 6;
                    $quantity = $cart[$i][1];
                    break;
                };
            }
            if ($i==count($cart)){
                array_push($cart, [intval($bottleId), 6]);
                $quantity = 6;
                $isHidden = true;
            }
            $session->set('cart',$cart);
            return  new JsonResponse([$isHidden, $quantity]);
        }
        return new JsonResponse('no results found', Response::HTTP_NOT_FOUND);

    }

    /**
     * @Route("/RemoveOneProduct", name="remove_one_product")
     */
    public function removeOneProduct(SessionInterface $session, Request $request){
        if ($request->isXmlHttpRequest()) {
            $cart = $session->get('cart');
            $bottleId = $request->query->get('bottleId');
            for ($i=0 ; $i < count($cart); $i++) {
                if($cart[$i][0]==$bottleId && $cart[$i][1]!=6){
                    $cart[$i][1] = $cart[$i][1] - 6;
                    break;
                }
                else if($cart[$i][0]==$bottleId && $cart[$i][1]==6){
                    //array_splice($cart, $i,1);
                    break;
                }
            }
            $session->set('cart',$cart);


            return  new JsonResponse($cart);
        }
        return new JsonResponse('no results found', Response::HTTP_NOT_FOUND);

    }

    /**
     * @Route("/RemoveAllProducts", name="remove_all_products")
     */
    public function removeAllProduct(SessionInterface $session, Request $request){
        if ($request->isXmlHttpRequest()) {
            $cart = $session->get('cart');
            $bottleId = $request->query->get('bottleId');
            for ($i=0 ; $i < count($cart); $i++) {
                if($cart[$i][0]==$bottleId){
                    array_splice($cart, $i,1);
                    break;
                }
            }
            $session->set('cart',$cart);
            return  new JsonResponse($cart);
        }
        return new JsonResponse('no results found', Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route("/ClearCart", name="clear_cart")
     */
    public function clearCart(SessionInterface $session){
        $session->set('cart',[]);
        return new Response('ok');
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


        $repository = $this->getDoctrine()->getRepository(Champagne::class);
        $list = $repository->findBy(
            ['name'=>"Carte d'Or"]
        );

    }


}