<?php

namespace App\Form\Type;

use App\Persistence\Doctrine\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $roles = [
            'Maker' => 'ROLE_USER',
            'Admin' => 'ROLE_ADMIN',
            'Repartidor' => 'ROLE_DELIVERY',
        ];
        $builder
            ->add('nick', TextType::class, ['required' => false, 'label' => 'Nick'])
            ->add('roles', ChoiceType::class, [
                'label' => 'Roles * - Puedes seleccionar varios',
                'choices' => $roles,
                'multiple' => true,
            ])
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
