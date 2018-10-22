<?php
/**
 * Created by IntelliJ IDEA.
 * User: antoine
 * Date: 17/09/2018
 * Time: 13:44
 */

namespace App\Controller;

use App\Form\UserType;
use App\Entity\User;
use App\Service\CartManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/Inscription", name="user_registration")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, SessionInterface $session, ValidatorInterface $validator, CartManager $cartManager)
    {

        $cartSize = $cartManager->cartSize();
        $user = new User();
        $form = $this->createForm(UserType::class, $user, array('validation_groups' => array('registration', 'Default')));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $success = "Votre compte a bien été crée !";
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->container->get('security.token_storage')->setToken($token);
            $this->container->get('session')->set('_security_main', serialize($token));
            $user = $this->getUser();
            return $this->render(
                'view/compte.html.twig', [
                    'cartSize' => $cartSize,
                    'success' => $success,
                    'user' => $user
                ]
            );
        }
        elseif ($form->isSubmitted() && !$form->isValid()){


            $errors = $validator->validate($user);

            if (count($errors) > 0) {
                /*
                 * Uses a __toString method on the $errors variable which is a
                 * ConstraintViolationList object. This gives us a nice string
                 * for debugging.
                 */
                $errorsString = (string)$errors;
            }


            return $this->render(
                'security/register.html.twig', [
                    'form' => $form->createView(),
                    'cartSize' => $cartSize,
                    'error' => $errorsString
                ]
            );
        }

        return $this->render(
            'security/register.html.twig', [
                'form' => $form->createView(),
                'cartSize' => $cartSize
                ]
        );
    }
}