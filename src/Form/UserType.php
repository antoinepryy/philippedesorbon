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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('civility', ChoiceType::class,
                ['choices'=>['Monsieur'=>'Monsieur','Madame'=>'Madame']])
            ->add('email', EmailType::class,
                ['attr'=>['placeholder'=>'Email']])
            ->add('firstName', TextType::class,
                ['attr'=>['placeholder'=>'Prénom']])
            ->add('lastName', TextType::class,
                ['attr'=>['placeholder'=>'Nom']])
            ->add('phoneNumber', TelType::class,
                ['attr'=>['placeholder'=>'Téléphone']])
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Mot de passe', 'attr'=>['placeholder'=>'Mot de passe']),
                'second_options' => array('label' => 'Confirmer mot de passe', 'attr'=>['placeholder'=>'Confirmer mot de passe']),
            ))
            ->add('addressStreet', TextType::class,
                ['attr'=>['placeholder'=>'Adresse']])
            ->add('addressCity', TextType::class,
                ['attr'=>['placeholder'=>'Ville']])
            ->add('addressZipCode', IntegerType::class,
                ['attr'=>['placeholder'=>'Code Postal']])
            ->add('addressCountry', CountryType::class,
                ['attr'=>['placeholder'=>'Pays']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}