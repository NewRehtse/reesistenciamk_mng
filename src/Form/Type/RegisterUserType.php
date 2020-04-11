<?php

namespace App\Form\Type;

use App\Persistence\Doctrine\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, ['label' => 'E-Mail *'])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Contraseña *'],
                'second_options' => ['label' => 'Repite Contraseña *'],
            ])
            ->add('userType', ChoiceType::class, [
                'label' => 'Tipo de usuario:',
                'data' => ['maker'],
                'choices' => [
                    'Maker' => 'maker',
                    'Repartidor' => 'delivery',
                    'Sanitario' => 'health',
                ],
                'multiple' => true,
            ])
            ->add('save', SubmitType::class, ['label' => 'Registrarse', 'attr' => ['class' => 'login100-form-btn']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
