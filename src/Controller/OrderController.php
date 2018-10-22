<?php
/**
 * Created by IntelliJ IDEA.
 * User: antoi
 * Date: 18/10/2018
 * Time: 13:19
 */

namespace App\Controller;


use App\Entity\Champagne;
use App\Entity\ChampagneOption;
use App\Service\CartManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class OrderController extends Controller
{

    public function orderValidated(\Swift_Mailer $mailer, SessionInterface $session, CartManager $cartManager, $method)
    {
        $cart = $session->get('cart');
        $user = $this->getUser();
        $orderPrice = $cartManager->totalCalculation();
        $orderContent = $cartManager->orderContent();
        $cartSize = $cartManager->cartSize();

        switch ($method){
            case 'Virement':
                break;
            case 'Cheque':
                break;
            case 'CRCA':
                break;
        }

        $messageClient = (new \Swift_Message('Philippe de Sorbon'))
            ->setFrom('philippedesorbon@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                    'emails/orderClient'.$method.'.html.twig', [
                        'orderContent' => $orderContent,
                        'totalPrice' => $orderPrice,
                        'client' => $user
                    ]
                ),
                'text/html'
            );

        $messageAdmin = (new \Swift_Message('Philippe de Sorbon'))
            ->setFrom('philippedesorbon@gmail.com')
            ->setTo('philippedesorbon@gmail.com')
            ->setBody(
                $this->renderView(
                    'emails/orderAdmin'.$method.'.html.twig', [
                        'orderContent' => $orderContent,
                        'totalPrice' => $orderPrice,
                        'client' => $user
                    ]
                ),
                'text/html'
            );

        if ($cart != []) {
            $mailer->send($messageClient);
            $mailer->send($messageAdmin);
            $session->set('cart', []);
        }

        return $this->render('view/validOrder.html.twig', [
            'cartSize' => $cartSize
        ]);
    }

    public function checkout(SessionInterface $session, Request $request, CartManager $cartManager)
    {
        $orderPrice = $cartManager->totalCalculation();
        $user = $this->getUser();
        $cartSize = $cartManager->cartSize();
        $defaultData = [];
        $form = $this->createFormBuilder($defaultData)
            ->add('paymentMethod', ChoiceType::class,[
                    'label' => 'Moyen de paiement',
                    'choices' => array('Virement' => 'virement', 'Chèque' => 'cheque', 'Paiement en ligne (disponible prochainement)' => 'CRCA'),
                    'choice_attr' => function($choiceValue, $key, $value) {
                        if($value == 'CRCA'){
                            return ['disabled' => 'disabled'];
                        }
                        else{
                            return [];
                        }

                    }
                ]
            )
            ->add('Confirmer', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            switch ($data['paymentMethod']){
                case 'virement':
                    return $this->redirectToRoute('order_validated',[
                        'method' => 'Virement'
                    ]);
                    break;
                case 'cheque':
                    return $this->redirectToRoute('order_validated',[
                        'method' => 'Cheque'
                    ]);
                    break;
                case 'CRCA':
                    return $this->redirectToRoute('online_payment');
                    break;

            }
        }
        return $this->render('view/checkout.html.twig', [
            'cartSize' => $cartSize,
            'form' => $form->createView(),
            'total' => $orderPrice,
            'user' => $user
        ]);
    }

}