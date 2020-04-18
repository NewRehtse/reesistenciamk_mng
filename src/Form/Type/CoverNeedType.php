<?php

namespace App\Form\Type;

use App\Persistence\Doctrine\Entity\Needs;
use App\Persistence\Doctrine\Repository\PlaceRepository;
use App\Persistence\Doctrine\Repository\ThingRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoverNeedType extends AbstractType
{
    /** @var PlaceRepository */
    private $placeRepository;
    /** @var ThingRepository */
    private $thingRepository;

    public function __construct(PlaceRepository $placeRepository, ThingRepository $thingRepository)
    {
        $this->placeRepository = $placeRepository;
        $this->thingRepository = $thingRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('covered', IntegerType::class, ['label' => 'Cantidad'])
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
