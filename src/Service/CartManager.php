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
use http\Env\Response;
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
          foreach ($cart as $champagne) {
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
          return $orderPrice;
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
                array_push($orderContent, $champagne[1] . ' x ' . $champagneModel->getName());
            }
        }

        return $orderContent;
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