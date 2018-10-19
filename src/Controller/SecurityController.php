<?php
/**
 * Created by IntelliJ IDEA.
 * User: antoine
 * Date: 17/09/2018
 * Time: 14:26
 */

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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

                $success = "Un mail a été envoyé à l'addresse correspondante !";

                $generatedHash = sha1(uniqid());
                $foundUser->setPasswordLink($generatedHash);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($foundUser);
                $entityManager->flush();

                $messageClient = (new \Swift_Message('Récupération de mot de passe'))
                    ->setFrom('philippedesorbon@gmail.com')
                    ->setTo($foundUser->getEmail())
                    ->setBody(
                        $this->renderView(
                            'emails/passwordLink.html.twig',[
                                'hashCode' => $generatedHash
                            ]
                        ),
                        'text/html'
                    );

                $mailer->send($messageClient);
                return $this->render('security/forgotPassword.html.twig', [
                    'cartSize' => $cartSize,
                    'form' => $form->createView(),
                    'success' => $success
                ]);


            }
            else{
                $error = "Addresse Email invalide";
                return $this->render('security/forgotPassword.html.twig', [
                    'cartSize' => $cartSize,
                    'error' => $error,
                    'form' => $form->createView(),
                ]);
            }

        }
        return $this->render('security/forgotPassword.html.twig', [
            'cartSize' => $cartSize,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/RecupererMotDePasse/{hashCode}", name="recover_password")
     */
    public function recoverPassword(SessionInterface $session, Request $request, UserPasswordEncoderInterface $passwordEncoder, $hashCode){
        if ($session->has('cart')) {
            $cartSize = count($session->get('cart'));
        } else {
            $cartSize = 0;
        }
        $repository = $this->getDoctrine()->getRepository(User::class);
        $foundUser = $repository->findOneBy([
            'passwordLink' => $hashCode
        ]);
        if ($foundUser != null ){
            $form = $this->createFormBuilder([])
                ->add('plainPassword', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'first_options'  => array('label' => 'Mot de passe', 'attr'=>['placeholder'=>'Mot de passe']),
                    'second_options' => array('label' => 'Confirmer mot de passe', 'attr'=>['placeholder'=>'Confirmer mot de passe']),
                ))
                ->add('Valider', SubmitType::class)
                ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $success = "Mot de passe modifié !";
                $data = $form->getData();
                $password = $passwordEncoder->encodePassword($foundUser, $data['plainPassword']);
                $foundUser->setPassword($password);
                $foundUser->setPasswordLink(null);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($foundUser);
                $entityManager->flush();

                return $this->render('security/forgotPassword.html.twig', [
                    'cartSize' => $cartSize,
                    'form' => $form->createView(),
                    'success' => $success
                ]);
            }

            return $this->render('security/forgotPassword.html.twig', [
                'cartSize' => $cartSize,
                'form' => $form->createView(),
            ]);


        }
        else{
            throw $this->createNotFoundException('Code non valide');
        }


    }

    /**
     * @Route("/MonCompte", name="compte")
     */
    public function account(SessionInterface $session, Request $request)
    {
        $cart = $session->get('cart');
        if ($session->has('cart')) {
            $cartSize = count($session->get('cart'));
        } else {
            $cartSize = 0;
        }

        $user = $this->getUser();
        return $this->render('view/compte.html.twig',
            [
                'cartSize' => $cartSize,
                'user' => $user
            ]);
    }

    /**
     * @Route("/ChangementMotDePasse", name="change_password")
     */
    public function changePassword(SessionInterface $session, Request $request ){
        $cart = $session->get('cart');
        if ($session->has('cart')) {
            $cartSize = count($session->get('cart'));
        } else {
            $cartSize = 0;
        }
        $form = $this->createFormBuilder([])
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Mot de passe', 'attr'=>['placeholder'=>'Mot de passe']),
                'second_options' => array('label' => 'Confirmer mot de passe', 'attr'=>['placeholder'=>'Confirmer mot de passe']),
            ))
            ->add('Valider', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $success = "Mot de passe modifié !";
            $data = $form->getData();
//            $password = $passwordEncoder->encodePassword($foundUser, $data['plainPassword']);
//            $foundUser->setPassword($password);
//            $foundUser->setPasswordLink(null);
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($foundUser);
//            $entityManager->flush();

            return $this->render('security/forgotPassword.html.twig', [
                'cartSize' => $cartSize,
                'form' => $form->createView(),
                'success' => $success
            ]);
        }

        return $this->render('security/forgotPassword.html.twig', [
            'cartSize' => $cartSize,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ModifierInformations", name="change_infos")
     */
    public function changeInfos(SessionInterface $session, Request $request ){
        $cart = $session->get('cart');
        if ($session->has('cart')) {
            $cartSize = count($session->get('cart'));
        } else {
            $cartSize = 0;
        }
        $form = $this->createFormBuilder([])
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Mot de passe', 'attr'=>['placeholder'=>'Mot de passe']),
                'second_options' => array('label' => 'Confirmer mot de passe', 'attr'=>['placeholder'=>'Confirmer mot de passe']),
            ))
            ->add('Valider', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $success = "Mot de passe modifié !";
            $data = $form->getData();
            //$password = $passwordEncoder->encodePassword($foundUser, $data['plainPassword']);
            //$foundUser->setPassword($password);
            //$foundUser->setPasswordLink(null);
            //$entityManager = $this->getDoctrine()->getManager();
            //$entityManager->persist($foundUser);
            //$entityManager->flush();

            return $this->render('security/forgotPassword.html.twig', [
                'cartSize' => $cartSize,
                'form' => $form->createView(),
                'success' => $success
            ]);
        }

        return $this->render('security/forgotPassword.html.twig', [
            'cartSize' => $cartSize,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/MesCommandes", name="my_orders")
     */
    public function myOrders(SessionInterface $session, Request $request ){
        $cart = $session->get('cart');
        if ($session->has('cart')) {
            $cartSize = count($session->get('cart'));
        } else {
            $cartSize = 0;
        }

        return $this->render('security/forgotPassword.html.twig');
    }
}