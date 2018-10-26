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
use App\Entity\Commande;
use App\Service\CartManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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

        $cartSize = $cartManager->cartSize();

        return $this->render('view/validOrder.html.twig', [
            'cartSize' => $cartSize
        ]);
    }

    public function checkout(SessionInterface $session, Request $request, CartManager $cartManager)
    {
        if($cartManager->isEmpty() == true){
            return $this->redirectToRoute('boutique');
        }

        $curentOrder = new Commande();
        $orderPrice = $cartManager->totalCalculation();
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $userName = $user->getFirstName()." ".$user->getLastName();
        $curentOrder->setAddressNameFact($userName);
        $curentOrder->setAddressCityFact($user->getAddressCity());
        $curentOrder->setAddressCountryFact($user->getAddressCountry());
        $curentOrder->setAddressStreetFact($user->getAddressStreet());
        $curentOrder->setAddressZipCodeFact($user->getAddressZipCode());
        $curentOrder->setPrice($orderPrice);
        $cartSize = $cartManager->cartSize();
//        return $this->render('dev.html.twig',[
//            'cartSize' => $cartSize
//        ]);
        $curentOrder->setBuyer($user);
        $form = $this->createFormBuilder($curentOrder)
            ->add('addressNameDelivery', TextType::class, [
                'attr'=>['placeholder'=>'Adresse'],
                'data'=>$userName
            ])
            ->add('addressStreetDelivery', TextType::class, [
                'attr'=>['placeholder'=>'Adresse'],
                'data'=>$user->getAddressStreet()
                ])
            ->add('addressCityDelivery', TextType::class, [
                'attr'=>['placeholder'=>'Ville'],
                'data'=>$user->getAddressCity()
            ])
            ->add('addressZipCodeDelivery', IntegerType::class, [
                'attr'=>['placeholder'=>'Code Postal'],
                'data'=>$user->getAddressZipCode()
            ])
            ->add('addressCountryDelivery', CountryType::class, [
                'attr'=>['placeholder'=>'Pays'],
                'data'=>$user->getAddressCountry()
            ])
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
            switch ($curentOrder->getPaymentMethod()){
                case 'virement':
                    $curentOrder->setPaymentMethod('virement');
                    $entityManager->persist($curentOrder);
                    $entityManager->flush();
                    return $this->redirectToRoute('order_validated',[
                        'method' => 'Virement'
                    ]);
                    break;
                case 'cheque':
                    $curentOrder->setPaymentMethod('cheque');
                    $entityManager->persist($curentOrder);
                    $entityManager->flush();
                    return $this->redirectToRoute('order_validated',[
                        'method' => 'Cheque'
                    ]);
                    break;
                case 'CRCA':
                    $curentOrder->setPaymentMethod('CRCA');
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