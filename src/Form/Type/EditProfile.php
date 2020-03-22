<?php

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class EditProfile extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $roles = [
            'Maker' => 'ROLE_USER',
            'Admin' => 'ROLE_ADMIN',
            'Repartidor' => 'ROLE_DELIVERY',
        ];
        $builder
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Contraseña *'],
                'second_options' => ['label' => 'Repite Contraseña *'],
            ])
            ->add('nick', TextType::class, ['required' => false, 'label' => 'Nick'])
            ->add('address', TextType::class, ['required' => false, 'label' => 'Dirección'])
            ->add('city', TextType::class, ['required' => false, 'label' => 'Ciudad'])
            ->add('postalCode', TextType::class, ['required' => false, 'label' => 'Código Postal'])
            ->add('phoneNumber', TextType::class, ['required' => false, 'label' => 'Teléfono'])
            ->add('nickTelegram', TextType::class, ['required' => false, 'label' => 'Nick telegram'])
            ->add('save', SubmitType::class, ['label' => 'Crear usuario', 'attr' => ['class' => 'login100-form-btn']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
