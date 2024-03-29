<?php
/**
 * Created by IntelliJ IDEA.
 * User: antoine
 * Date: 19/09/2018
 * Time: 00:00
 */

namespace App\Controller;

use App\Entity\Champagne;
use App\Entity\ChampagneOption;
use App\Entity\Option;
use App\Service\CartManager;
use Symfony\Component\Config\Definition\Exception\Exception;
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
    public function getCart(SessionInterface $session){

        // cart scheme => [[id champ, qtt, id option  (optional) ], .. ]
        // return  => [[id champ, qtt, price (optional) ], .. ]

        $cart = $session->get('cart');
        if (is_null($cart)){ // si pas de panier
            $cart = [];
            $session->set('cart',$cart);
        }
        return  new JsonResponse($cart);
    }

    /**
     * @Route("/PreOrder", name="pre_order")
     */
    public function preOrder(SessionInterface $session,Request $request){
        $cart = $session->get('cart');
        $bottleId = $request->query->get('bottleId');
        $optionRepository = $this->getDoctrine()->getRepository(ChampagneOption::class);
        $options = $optionRepository->findBy(
            ['champagne'=>$bottleId]
        );

        $hasOptions = isset($options[0]);
        if ($hasOptions){ // Si le champagne a des déclinaisons
            $optionsTab = [];
            for ($i=0 ; $i < count($options); $i++) {
                array_push($optionsTab, [$options[$i]->getId(),$options[$i]->getName(), $options[$i]->getPrice()]);
            }
            return  new JsonResponse([true, $optionsTab]); //Sinon renvoie tableau des options
        }
        else{ // Si la cuvée n'a pas de déclinaisons
            return  new JsonResponse([false]);
        }


    }

    /**
     * @Route("/AddProduct", name="add_product")
     */
    public function addProduct(SessionInterface $session,Request $request){
            $isHidden = false;
            $quantity = 0;
            $cart = $session->get('cart');
            $bottleId = $request->query->get('bottleId');
            $champagneOption = $request->query->get('champagneOption');
            $champagneRepository = $this->getDoctrine()->getRepository(Champagne::class);
            $optionRepository = $this->getDoctrine()->getRepository(ChampagneOption::class);
            $optionPrice = null;

            for ($i=0 ; $i < count($cart); $i++) {
                if($cart[$i][0]==$bottleId && count($cart[$i])==2){ // Si possibilité de baisser la qqt
                    $step = intval($champagneRepository->find($bottleId)->getStepOrder());
                    $cart[$i][1] = $cart[$i][1] + $step;
                    break;
                }
                else if($cart[$i][0]==$bottleId && $cart[$i][2]==$champagneOption && count($cart[$i]) == 3){ // Si pas possible de baisser la qtt
                    $step = intval($optionRepository->find($cart[$i][2])->getStepOrder());
                    $cart[$i][1] = $cart[$i][1] + $step;
                    break;
                }
            }
            if ($i==count($cart)){
                if($champagneOption == null){
                    $step = intval($champagneRepository->find($bottleId)->getStepOrder());
                    array_push($cart, [intval($bottleId), $step]);
                }
                else{
                    $step = intval($optionRepository->find($champagneOption)->getStepOrder());
                    $optionPrice = $optionRepository->find($champagneOption)->getPrice();
                    array_push($cart, [intval($bottleId), $step, $champagneOption]);
                }
                $quantity = $step;
                $isHidden = true;
            }

            $session->set('cart',$cart);
            return  new JsonResponse([$isHidden, $quantity, $cart, $optionPrice, $step]);

    }

    /**
     * @Route("/RemoveOneProduct", name="remove_one_product")
     */
    public function removeOneProduct(SessionInterface $session, Request $request){
        if ($request->isXmlHttpRequest()) {
            $cart = $session->get('cart');
            $bottleId = $request->query->get('bottleId');
            $champagneOption = $request->query->get('champagneOption');
            for ($i=0 ; $i < count($cart); $i++) {
                if($cart[$i][0]==$bottleId && $champagneOption=="undefined"){
                    $champagneRepository = $this->getDoctrine()->getRepository(Champagne::class);
                    $step = intval($champagneRepository->find($bottleId)->getStepOrder());
                    if ($cart[$i][1]!=$step){
                        $cart[$i][1] = $cart[$i][1] - $step;
                    }
                    else{
                        continue;
                    }
                    break;
                }
                else if($cart[$i][0]==$bottleId && $champagneOption==$cart[$i][2]){
                    $optionRepository = $this->getDoctrine()->getRepository(ChampagneOption::class);
                    $step = intval($optionRepository->find($champagneOption)->getStepOrder());
                    if ($cart[$i][1]!=$step){
                        $cart[$i][1] = $cart[$i][1] - $step;
                    }
                    else{
                        continue;
                    }
                    break;
                }
            }
            $session->set('cart',$cart);
            return  new JsonResponse([$cart, $step]);
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
            $champagneOption = $request->query->get('champagneOption');
            for ($i=0 ; $i < count($cart); $i++) {
                if($cart[$i][0]==$bottleId && $champagneOption=="undefined"){
                    array_splice($cart, $i,1);
                    break;
                }
                elseif ($cart[$i][0]==$bottleId && $champagneOption==$cart[$i][2]){
                    array_splice($cart, $i,1);
                    break;
                }
            }
            $session->set('cart',$cart);
            return  new JsonResponse([$cart]);
        }
        return new JsonResponse('no results found', Response::HTTP_NOT_FOUND);
    }

}

