<?php

namespace App\Form\Type;

use App\Persistence\Doctrine\Entity\Thing;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditThingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', TextType::class,
                [
                    'label' => 'Tipo',
                    'attr' => ['place_holder' => 'Ej: filtro, mascarilla, pantalla, etc'],
                ])
            ->add('model', TextType::class,
                [
                    'label' => 'Modelo',
                    'attr' => ['place_holder' => 'Ej: modelo grande, pequeño, x23, etc'],
                ])
            ->add('description', TextareaType::class,
                [
                    'label' => 'Descripción',
                    'required' => false,
                ])
            ->add('urlThingiverse', TextType::class,
                [
                    'label' => 'Url de modelo',
                    'required' => false,
                    'attr' => ['place_holder' => 'Ej: Url de thingiverse'],
                ])
            ->add('otherUrl', TextType::class,
                [
                    'label' => 'Otra url',
                    'required' => false,
                ])
            ->add('photoUrl', TextType::class,
                [
                    'label' => 'Url de la foto',
                    'required' => false,
                ])
            ->add('save', SubmitType::class, ['label' => 'Guardar'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Thing::class,
        ]);
    }
}
