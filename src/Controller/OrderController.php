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

    public function orderValidated(\Swift_Mailer $mailer, SessionInterface $session, CartManager $cartManager)
    {
        $cart = $session->get('cart');
        $user = $this->getUser();
        $orderPrice = $cartManager->totalCalculation();
        $orderContent = $cartManager->orderContent();

        $messageClient = (new \Swift_Message('Philippe de Sorbon'))
            ->setFrom('philippedesorbon@gmail.com')
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
            ->setFrom('philippedesorbon@gmail.com')
            ->setTo('philippedesorbon@gmail.com')
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

        if ($cart != []) {
            $mailer->send($messageClient);
            $mailer->send($messageAdmin);
            $session->set('cart', []);
        }

        return $this->render('view/validOrder.html.twig', [
            'cartSize' => 0
        ]);
    }

    public function checkout(SessionInterface $session, Request $request)
    {
        if ($session->has('cart')) {
            $cartSize = count($session->get('cart'));
        } else {
            $cartSize = 0;
        }

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
                    return $this->redirectToRoute('order_validated');
                    break;
                case 'cheque':
                    return $this->redirectToRoute('order_validated');
                    break;
                case 'CRCA':
                    break;

            }
            return $this->redirectToRoute('order_validated');
        }
        return $this->render('view/checkout.html.twig', [
            'cartSize' => $cartSize,
            'form' => $form->createView(),
        ]);
    }

}