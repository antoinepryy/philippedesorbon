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
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class OrderController extends Controller
{
    public function checkout(SessionInterface $session, Request $request, CartManager $cartManager, ValidatorInterface $validator)
    {
        $orderCanceled = false;
        if ($cartManager->isEmpty() == true) {
            return $this->redirectToRoute('boutique');
        }


        $entityManager = $this->getDoctrine()->getManager();
        if ($session->has('idOrderCRCA') || $session->has('priceOrderCRCA')){//Si commande en attente
            $commandeRepo = $this->getDoctrine()->getRepository(Commande::class);
            $cancelledOrder= $commandeRepo->find($session->get('idOrderCRCA'));
            $entityManager->remove($cancelledOrder);
            $entityManager->flush();
            $session->remove('idOrderCRCA');
            $session->remove('priceOrderCRCA');
            $orderCanceled = true;
        }

        $curentOrder = new Commande();
        $orderPrice = $cartManager->totalCalculation();
        $orderContentArray = $cartManager->orderContentWithPrice();
        $user = $this->getUser();
        $userName = $user->getFirstName() . " " . $user->getLastName();

        $curentOrder->setPrice($orderPrice);
        $cartSize = $cartManager->cartSize();

        $curentOrder->setBuyer($user);
        $form = $this->createFormBuilder($curentOrder)
            ->add('addressNameFact', TextType::class, [
                'attr' => ['placeholder' => 'Nom'],
                'data' => $userName
            ])
            ->add('addressStreetFact', TextType::class, [
                'attr' => ['placeholder' => 'Adresse de facturation'],
                'data' => $user->getAddressStreet()
            ])
            ->add('addressCityFact', TextType::class, [
                'attr' => ['placeholder' => 'Ville'],
                'data' => $user->getAddressCity()
            ])
            ->add('addressZipCodeFact', IntegerType::class, [
                'attr' => ['placeholder' => 'Code Postal'],
                'data' => $user->getAddressZipCode()
            ])
            ->add('addressCountryFact', CountryType::class, [
                'attr' => ['placeholder' => 'Pays'],
                'data' => $user->getAddressCountry()
            ])
            ->add('telFact', TelType::class, [
                'attr' => ['placeholder' => 'Téléphone'],
                'data' => $user->getPhoneNumber()
            ])
            ->add('addressNameDelivery', TextType::class, [
                'attr' => ['placeholder' => 'Adresse de livraison'],
                'data' => $userName
            ])
            ->add('addressStreetDelivery', TextType::class, [
                'attr' => ['placeholder' => 'Adresse'],
                'data' => $user->getAddressStreet()
            ])
            ->add('addressCityDelivery', TextType::class, [
                'attr' => ['placeholder' => 'Ville'],
                'data' => $user->getAddressCity()
            ])
            ->add('addressZipCodeDelivery', IntegerType::class, [
                'attr' => ['placeholder' => 'Code Postal'],
                'data' => $user->getAddressZipCode()
            ])
            ->add('addressCountryDelivery', CountryType::class, [
                'attr' => ['placeholder' => 'Pays'],
                'data' => 'FR',
                'disabled' => true
            ])
            ->add('telDelivery', TelType::class, [
                'attr' => ['placeholder' => 'Téléphone'],
                'data' => $user->getPhoneNumber()
            ])
            ->add('dateDelivery', DateType::class, [
                'widget' => 'single_text',
                'required'=>false
            ])
            ->add('paymentMethod', ChoiceType::class, [
                    'label' => 'Moyen de paiement',
                    'choices' => array('Virement' => 'virement', 'Chèque' => 'cheque', 'Paiement en ligne' => 'CRCA'),
                ]
            )
            ->add('Confirmer', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            switch ($curentOrder->getPaymentMethod()) {
                case 'virement':
                    $curentOrder->setPaymentMethod('virement');
                    $curentOrder->setDateTime(new \DateTime());
                    $curentOrder->setAddressCountryDelivery('FR');
                    $curentOrder->setContent($cartManager->arrayToLongTextOrderPrice());
                    $entityManager->persist($curentOrder);
                    $entityManager->flush();
                    return $this->redirectToRoute('order_validated', [
                        'method' => 'Virement'
                    ]);
                    break;
                case 'cheque':
                    $curentOrder->setPaymentMethod('cheque');
                    $curentOrder->setDateTime(new \DateTime());
                    $curentOrder->setContent($cartManager->arrayToLongTextOrderPrice());
                    $curentOrder->setAddressCountryDelivery('FR');
                    $entityManager->persist($curentOrder);
                    $entityManager->flush();
                    return $this->redirectToRoute('order_validated', [
                        'method' => 'Cheque'
                    ]);
                    break;
                case 'CRCA':
                    $curentOrder->setPaymentMethod('CRCA');
                    $curentOrder->setDateTime(new \DateTime());
                    $curentOrder->setContent($cartManager->arrayToLongTextOrderPrice());
                    $curentOrder->setAddressCountryDelivery('FR');
                    $entityManager->persist($curentOrder);
                    $entityManager->flush();
                    $session->set('idOrderCRCA', $curentOrder->getId());
                    $session->set('priceOrderCRCA', sprintf("%01.2f", $orderPrice));
                    return $this->redirectToRoute('online_payment');
                    break;

            }
        } elseif ($form->isSubmitted() && !$form->isValid()){
            $errors = $validator->validate($curentOrder);

            if (count($errors) > 0) {
                return $this->render('view/checkout.html.twig', [
                    'cartSize' => $cartSize,
                    'form' => $form->createView(),
                    'total' => $orderPrice,
                    'user' => $user,
                    'content' => $orderContentArray,
                    'errors' => $errors
                ]);
            }

        }
        return $this->render('view/checkout.html.twig', [
            'cartSize' => $cartSize,
            'form' => $form->createView(),
            'total' => $orderPrice,
            'user' => $user,
            'content' => $orderContentArray,
            'orderCanceled' => $orderCanceled
        ]);


    }

    public function orderValidated(\Swift_Mailer $mailer, SessionInterface $session, CartManager $cartManager, $method)
    {
        $cart = $session->get('cart');


        if ($session->has('idOrderCRCA') || $session->has('priceOrderCRCA')){//Si commande en attente
            $session->remove('idOrderCRCA');
            $session->remove('priceOrderCRCA');
        }

        $user = $this->getUser();
        $orderPrice = $cartManager->totalCalculation();
        $orderContent = $cartManager->orderContentWithPrice();


        $messageClient = (new \Swift_Message('Philippe de Sorbon'))
            ->setFrom('philippedesorbon@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                    'emails/orderClient' . $method . '.html.twig', [
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
                    'emails/orderAdmin' . $method . '.html.twig', [
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
}