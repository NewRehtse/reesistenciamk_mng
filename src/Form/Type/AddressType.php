<?php

namespace App\Form\Type;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address1', TextType::class, ['required' => true, 'label' => 'Linea 1'])
            ->add('address2', TextType::class, ['required' => false, 'label' => 'Linea 2'])
            ->add('city', TextType::class, ['required' => false, 'label' => 'Ciudad'])
            ->add('postalCode', TextType::class, ['required' => false, 'label' => 'Código Postal'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
