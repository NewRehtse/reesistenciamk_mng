<?php

namespace App\Form\Type;

use App\Persistence\Doctrine\Entity\Configuration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('usersCanCreatePrints', CheckboxType::class, ['label' => 'Los usuarios pueden crear imprimibles', 'required' => false])
                ->add('save', SubmitType::class, ['label' => 'Guardar configuración', 'attr' => ['class' => 'login100-form-btn']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                'data_class' => Configuration::class,
        ]);
    }
}
