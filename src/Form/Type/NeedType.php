<?php

namespace App\Form\Type;

use App\Entity\Needs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class NeedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //TODO INjecctarrrr
        $places = $options['data']['places'] ?? [];
        $things = $options['data']['things'] ?? [];

        $builder
            ->add('place', ChoiceType::class, ['label' => 'Sitio',  'choices' => $places])
            ->add('thing', ChoiceType::class, ['label' => 'Imprimible',  'choices' => $things])
            ->add('amount', IntegerType::class)
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Needs::class,
        ]);
    }
}
