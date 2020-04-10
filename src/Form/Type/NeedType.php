<?php

namespace App\Form\Type;

use App\Persistence\Doctrine\Entity\Needs;
use App\Persistence\Doctrine\Entity\Place;
use App\Persistence\Doctrine\Entity\Thing;
use App\Persistence\Doctrine\Repository\PlaceRepository;
use App\Persistence\Doctrine\Repository\ThingRepository;
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
        $places = $this->placeRepository->findAll();
        $things = $this->thingRepository->findAll();

        $builder
            ->add('place', ChoiceType::class, [
                    'label' => 'Sitio',
                    'choices' => $places,
                    'choice_value' => function (?Place $place = null) {
                        return $place ? $place->id() : null;
                    },
                    'choice_label' => function (?Place $choice, $key, $value) {
                        if (null === $choice) {
                            return 'No parent';
                        }

                        return $choice->name();
                    },
                ])
            ->add('thing', ChoiceType::class, [
                    'label' => 'Imprimible',
                    'choices' => $things,
                    'choice_value' => function (?Thing $thing = null) {
                        return $thing ? $thing->id() : null;
                    },
                    'choice_label' => function (?Thing $choice, $key, $value) {
                        if (null === $choice) {
                            return 'No parent';
                        }

                        return $choice->model();
                    },
                ])
            ->add('amount', IntegerType::class, ['label' => 'Cantidad'])
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
