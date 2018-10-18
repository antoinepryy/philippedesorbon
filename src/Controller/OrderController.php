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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class OrderController extends Controller
{

    public function orderValidated(\Swift_Mailer $mailer, SessionInterface $session)
    {
        $champagneRepository = $this->getDoctrine()->getRepository(Champagne::class);
        $optionRepository = $this->getDoctrine()->getRepository(ChampagneOption::class);
        //$champagneListClassique = $repository->findBy(['type' => 'Classique']);
        $cart = $session->get('cart');
        $user = $this->getUser();
        $orderContent = [];
        $orderPrice = 0;

        foreach ($cart as $champagne) {
            if (count($champagne) === 3) {
                $champagneModel = $champagneRepository->findOneBy(['id' => $champagne[0]]);
                $champagneOption = $optionRepository->findOneBy(['id' => $champagne[2]]);
                $champagneQuantity = $champagne[1];
                array_push($orderContent, $champagneQuantity . ' x ' . $champagneModel->getName() . ' ' . $champagneOption->getName());
                $orderPrice += $champagneOption->getPrice() * $champagneQuantity;
            } else {
                $champagneQuantity = $champagne[1];
                $champagneModel = $champagneRepository->findOneBy(['id' => $champagne[0]]);
                array_push($orderContent, $champagne[1] . ' x ' . $champagneModel->getName());
                $orderPrice += $champagneModel->getPrice() * $champagneQuantity;
            }
        }

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