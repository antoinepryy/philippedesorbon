<?php
/**
 * Created by IntelliJ IDEA.
 * User: antoine
 * Date: 17/09/2018
 * Time: 14:26
 */

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
    public function changePassword(SessionInterface $session, Request $request,  UserPasswordEncoderInterface $passwordEncoder){

        if ($session->has('cart')) {
            $cartSize = count($session->get('cart'));
        } else {
            $cartSize = 0;
        }
        $user = $this->getUser();

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
            $data = $form->getData();
            $plainpass = $data['plainPassword'];
            $hashPass = $passwordEncoder->encodePassword($user, $plainpass);
            $success = "Mot de passe modifié avec succès !";
            $user->setPassword($hashPass);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->render('security/modifierMotDePasse.html.twig', [
                'cartSize' => $cartSize,
                'form' => $form->createView(),
                'success' => $success
            ]);

        }
        return $this->render('security/modifierMotDePasse.html.twig', [
            'cartSize' => $cartSize,
            'form' => $form->createView(),
        ]);
    }




    /**
     * @Route("/ModifierInformations", name="change_infos")
     */
    public function changeInfos(SessionInterface $session, Request $request ){
        if ($session->has('cart')) {
            $cartSize = count($session->get('cart'));
        } else {
            $cartSize = 0;
        }

        $user = $this->getUser();


        $form = $this->createFormBuilder([])
            ->add('civility', ChoiceType::class, [
                'choices'=>['Monsieur'=>'Monsieur','Madame'=>'Madame'],
                'label'=>'Civilité',
                ])
            ->add('email', EmailType::class, [
                'label'=>'Email',
                'data' => $user->getEmail()

            ])
            ->add('firstName', TextType::class, [
                'label'=>'Prénom',
                'data' => $user->getfirstName()
            ])
            ->add('lastName', TextType::class, [
                'label'=>'Nom',
                'data' => $user->getlastName()
                ]
            )
            ->add('addressStreet', TextType::class, [
                'label'=>'Addresse',
                'data' => $user->getaddressStreet()
            ])
            ->add('addressCity', TextType::class, [
                'label'=>'Ville',
                'data' => $user->getaddressCity()
            ])
            ->add('addressZipCode', TextType::class, [
                'label'=>'Code Postal',
                'data' => $user->getaddressZipCode()
            ])
            ->add('addressCountry', TextType::class, [
                'label'=>'Pays',
                'data' => $user->getaddressCountry()
            ])
            ->add('Valider', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $success = "Informations modifiées avec succès !";
            $data = $form->getData();
            $user->setcivility($data['civility']);
            $user->setfirstName($data['firstName']);
            $user->setlastName($data['lastName']);
            $user->setaddressStreet($data['addressStreet']);
            $user->setaddressCity($data['addressCity']);
            $user->setaddressCountry($data['addressCountry']);
            $user->setaddressZipCode($data['addressZipCode']);
            $user->setEmail($data['email']);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->render('security/modifierInfos.html.twig', [
                'cartSize' => $cartSize,
                'form' => $form->createView(),
                'success' => $success
            ]);

        }

        return $this->render('security/modifierInfos.html.twig', [
            'cartSize' => $cartSize,
            'form' => $form->createView()
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
        return $this->render('dev.html.twig',[
            'cartSize' => $cartSize
        ]);;

        return $this->render('security/mesCommandes.html.twig',[
            'cartSize' => $cartSize
        ]);
    }

    /**
     * @Route("/MesAddresses", name="my_addresses")
     */
    public function myAddresses(SessionInterface $session, Request $request ){
        $cart = $session->get('cart');

        if ($session->has('cart')) {
            $cartSize = count($session->get('cart'));
        } else {
            $cartSize = 0;
        }
        return $this->render('dev.html.twig',[
            'cartSize' => $cartSize
        ]);;
        return $this->render('security/mesAddresses.html.twig');
    }
}