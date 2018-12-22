<?php
/**
 * Created by IntelliJ IDEA.
 * User: antoine
 * Date: 22/10/2018
 * Time: 14:48
 */

namespace App\Service;


use App\Entity\Champagne;
use App\Entity\ChampagneOption;
use App\Repository\ChampagneOptionRepository;
use App\Repository\ChampagneRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\TransactionRequiredException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartManager
{
    public function __construct(SessionInterface $session, EntityManagerInterface $em, ChampagneRepository $champagneRepository, ChampagneOptionRepository $champagneOptionRepository)
    {
        $this->session = $session;
        $this->em = $em;
        $this->champagneRepository = $champagneRepository;
        $this->champagneOptionRepository = $champagneOptionRepository;
    }

    public function totalCalculation(){
          $cart = $this->session->get('cart');
          $orderPrice = 0;
          $nbrBottle = 0;
          $delivery = 0;
          foreach ($cart as $champagne) {
              $nbrBottle += $champagne[1];
              if (count($champagne) === 3) {
                  $champagnePrice = $this->champagneOptionRepository->findOneBy(['id' => $champagne[2]])->getPrice();
                  $champagneQuantity = $champagne[1];
                  $orderPrice += $champagnePrice * $champagneQuantity;
              }
              else {
                  $champagnePrice = $this->champagneRepository->findOneBy(['id' => $champagne[0]])->getPrice();
                  $champagneQuantity = $champagne[1];
                  $orderPrice += $champagnePrice * $champagneQuantity;
              }
          }
        if ( 0 < $nbrBottle && $nbrBottle< 12){
            $delivery = 22;
        }
        else if (11 < $nbrBottle && $nbrBottle< 18){
            $delivery = 30;
        }
        else if (17 < $nbrBottle && $nbrBottle< 24){
            $delivery = 36;
        }
        else{
            $delivery = 0;
        }
        return $orderPrice + $delivery;
    }

    public function orderContent(){
        $cart = $this->session->get('cart');
        $orderContent = [];
        foreach ($cart as $champagne) {
            if (count($champagne) === 3) {
                $champagneModel = $this->champagneRepository->findOneBy(['id' => $champagne[0]]);
                $champagneOption = $this->champagneOptionRepository->findOneBy(['id' => $champagne[2]]);
                $champagneQuantity = $champagne[1];
                array_push($orderContent, $champagneQuantity . ' x ' . $champagneModel->getName() . ' ' . $champagneOption->getName());
            } else {
                $champagneQuantity = $champagne[1];
                $champagneModel = $this->champagneRepository->findOneBy(['id' => $champagne[0]]);
                array_push($orderContent, $champagneQuantity . ' x ' . $champagneModel->getName());
            }
        }

        return $orderContent;
    }

    public function orderContentWithPrice(){
        $cart = $this->session->get('cart');
        $orderContent = [];
        foreach ($cart as $champagne) {
            if (count($champagne) === 3) {
                $champagneModel = $this->champagneRepository->findOneBy(['id' => $champagne[0]]);
                $champagneOption = $this->champagneOptionRepository->findOneBy(['id' => $champagne[2]]);
                $champagneQuantity = $champagne[1];
                $price = floatval($champagneQuantity * $champagneOption->getPrice());
                array_push($orderContent, $champagneQuantity . ' x ' . $champagneModel->getName() . ' ' . $champagneOption->getName().' : '.sprintf("%01.2f", $price).' €');
            } else {
                $champagneQuantity = $champagne[1];
                $champagneModel = $this->champagneRepository->findOneBy(['id' => $champagne[0]]);
                $price = floatval($champagneQuantity * $champagneModel->getPrice());

                array_push($orderContent, $champagneQuantity . ' x ' . $champagneModel->getName().' : '.sprintf("%01.2f", $price). ' €');
            }
        }

        return $orderContent;
    }

    public function arrayToLongTextOrderPrice(){
        $array = $this->orderContentWithPrice();
        $text = "";
        foreach ($array as $line){
            $text = $text.$line." \n \r ";
        }

        return nl2br($text);
    }

    public function cartSize(){
        if ($this->session->has('cart')) {
            $cartSize = count($this->session->get('cart'));
        } else {
            $cartSize = 0;
        }
        return $cartSize;
    }

    public function isEmpty(){
        if ($this->cartSize() == 0){
            return true;
        }
        else{
            return false;
        }
    }

}