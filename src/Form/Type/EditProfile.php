<?php

namespace App\Form\Type;

use App\Persistence\Doctrine\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditProfile extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nick', TextType::class, ['required' => false, 'label' => 'Nick'])
            ->add('address', AddressType::class, ['required' => false, 'label' => 'Dirección'])
            ->add('phoneNumber', TextType::class, ['required' => false, 'label' => 'Teléfono'])
            ->add('nickTelegram', TextType::class, ['required' => false, 'label' => 'Nick telegram'])
            ->add('save', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'login100-form-btn']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
