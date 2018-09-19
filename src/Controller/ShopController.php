<?php
/**
 * Created by IntelliJ IDEA.
 * User: antoine
 * Date: 19/09/2018
 * Time: 00:00
 */

namespace App\Controller;

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
    public function addProduct(SessionInterface $session, $id){
        $session->set('test',2);
        return $this->render('view/accueil.html.twig');
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
    public function clearCart(){

    }


}