<?php
/**
 * Created by IntelliJ IDEA.
 * User: antoine
 * Date: 17/09/2018
 * Time: 13:42
 */

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\IsTrue;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class,
                ['attr'=>['placeholder'=>'Email']])
            ->add('username', TextType::class,
                ['attr'=>['placeholder'=>'Pseudo']])
            ->add('firstName', TextType::class,
                ['attr'=>['placeholder'=>'Prénom']])
            ->add('lastName', TextType::class,
                ['attr'=>['placeholder'=>'Nom']])
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Mot de passe', 'attr'=>['placeholder'=>'Mot de passe']),
                'second_options' => array('label' => 'Confirmer mot de passe', 'attr'=>['placeholder'=>'Confirmer mot de passe']),
            ))
            ->add('addressStreet', TextType::class,
                ['attr'=>['placeholder'=>'Adresse']])
            ->add('addressCity', TextType::class,
                ['attr'=>['placeholder'=>'Ville']])
            ->add('addressZipCode', TextType::class,
                ['attr'=>['placeholder'=>'Code Postal']])
            ->add('addressCountry', TextType::class,
                ['attr'=>['placeholder'=>'Pays']])
            ->add('termsAccepted', CheckboxType::class, array(
                'mapped' => false,
                'constraints' => new IsTrue(),
                'label' => 'J\'accepte les termes d\'utilisation du site',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}