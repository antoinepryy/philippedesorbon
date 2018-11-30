<?php
/**
 * Created by IntelliJ IDEA.
 * User: antoine
 * Date: 17/09/2018
 * Time: 14:26
 */

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\User;
use App\Service\CartManager;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/Connexion", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils, SessionInterface $session, CartManager $cartManager)
    {
        $cartSize = $cartManager->cartSize();
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
    public function forgotPassword(SessionInterface $session, Request $request, \Swift_Mailer $mailer, CartManager $cartManager)
    {
        $cartSize = $cartManager->cartSize();
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
    public function recoverPassword(SessionInterface $session, Request $request, UserPasswordEncoderInterface $passwordEncoder, $hashCode, CartManager $cartManager){
        $cartSize = $cartManager->cartSize();
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
    public function account(SessionInterface $session, Request $request, CartManager $cartManager)
    {
        $cartSize = $cartManager->cartSize();

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
    public function changePassword(SessionInterface $session, Request $request,  UserPasswordEncoderInterface $passwordEncoder, CartManager $cartManager){

        $cartSize = $cartManager->cartSize();

        $user = $this->getUser();

        $form = $this->createFormBuilder($user, array('validation_groups' => array('change_password')))

            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Mot de passe', 'attr'=>['placeholder'=>'Mot de passe']),
                'second_options' => array('label' => 'Confirmer mot de passe', 'attr'=>['placeholder'=>'Confirmer mot de passe']),
            ))

            ->add('Valider', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $success = "Mot de passe modifié avec succès !";

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
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
    public function changeInfos(SessionInterface $session, Request $request, CartManager $cartManager ){
        $cartSize = $cartManager->cartSize();

        $user = $this->getUser();


        $form = $this->createFormBuilder($user, array('validation_groups' => array('change_infos')))
            ->add('civility', ChoiceType::class, [
                'choices'=>['Monsieur'=>'Monsieur','Madame'=>'Madame'],
                'data'=>$user->getCivility(),
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
            ->add('phoneNumber', TelType::class, [
                    'label'=>'Téléphone',
                    'data' => $user->getphoneNumber()
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
            ->add('addressZipCode', IntegerType::class, [
                'label'=>'Code Postal',
                'data' => $user->getaddressZipCode()
            ])
            ->add('addressCountry', CountryType::class, [
                'label'=>'Pays',
                'data' => $user->getaddressCountry()
            ])
            ->add('Valider', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $success = "Informations modifiées avec succès !";
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
    public function myOrders(SessionInterface $session, Request $request, CartManager $cartManager ){
        $cartSize = $cartManager->cartSize();

        $repository = $this->getDoctrine()->getRepository(Commande::class);
        $userOrders = $repository->findBy([
            'buyer' => $this->getUser()
        ]);


        return $this->render('security/mesCommandes.html.twig',[
            'cartSize' => $cartSize,
            'orders' => $userOrders
        ]);
    }

    /**
     * @Route("/Commande/{id}", name="order_content")
     */
    public function orderContent($id, SessionInterface $session, Request $request, CartManager $cartManager ){
        $cartSize = $cartManager->cartSize();

        $repository = $this->getDoctrine()->getRepository(Commande::class);
        $userOrder = $repository->find($id);

        if($userOrder->getBuyer() === $this->getUser()){
            return $this->render('security/orderContent.html.twig',[
                'cartSize' => $cartSize,
                'content' => $userOrder->getContent(),
                'price' => $userOrder->getPrice()
            ]);
        }
        else{
            throw $this->createNotFoundException('Cette commande n\'existe pas');
        }

    }



}