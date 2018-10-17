<?php
/**
 * Created by IntelliJ IDEA.
 * User: antoine
 * Date: 17/09/2018
 * Time: 14:26
 */

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/Connexion", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils, SessionInterface $session)
    {
        if ($session->has('cart')){
            $cartSize = count($session->get('cart'));
        }
        else{
            $cartSize = 0;
        }
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'cartSize' => $cartSize
        ));
    }

    /**
     * @Route("/MotDePasseOublie", name="forgot_password")
     */
    public function forgotPassword(SessionInterface $session, Request $request, \Swift_Mailer $mailer)
    {
        if ($session->has('cart')) {
            $cartSize = count($session->get('cart'));
        } else {
            $cartSize = 0;
        }

        $defaultData = [];
        $form = $this->createFormBuilder($defaultData)
            ->add('email', TextType::class,[
                    'label' => 'Adresse mail'
                ]
            )
            ->add('Valider', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository = $this->getDoctrine()->getRepository(User::class);
            $data = $form->getData();
            $foundUser = $repository->findOneBy([
                'email' => $data['email']
            ]);
            if ($foundUser != null){

                $messageClient = (new \Swift_Message('Philippe de Sorbon'))
                    ->setFrom('antoine.ap.57@gmail.com')
                    ->setTo($foundUser->getEmail())
                    ->setBody(
                        $this->renderView(
                            'emails/passwordLink.html.twig'
                        ),
                        'text/html'
                    );

                $mailer->send($messageClient);


            }
            else{
                $errorMessage = "Addresse Email invalide";
                return $this->render('security/forgotPassword.html.twig', [
                    'cartSize' => $cartSize,
                    'errorMessage' => $errorMessage
                ]);
            }

        }
        return $this->render('security/forgotPassword.html.twig', [
            'cartSize' => $cartSize,
            'form' => $form->createView(),
        ]);
    }
}