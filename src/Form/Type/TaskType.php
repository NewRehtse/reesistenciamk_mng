<?php

namespace App\Form\Type;

use App\Entity\Place;
use App\Entity\Task;
use App\Entity\Thing;
use App\Repository\PlaceRepository;
use App\Repository\ThingRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class TaskType extends AbstractType
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
        $deliveryTypes = [
            'Por definir' => Task::DELIVER_TYPE_UNDEFINED,
            'Recogida' => Task::DELIVER_TYPE_COLLECT,
            'Entregar' => Task::DELIVER_TYPE_DELIVER,
        ];

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
            ->add('amount', IntegerType::class)
            ->add('deliveryType', ChoiceType::class, ['choices' => $deliveryTypes])
            ->add('extra', TextType::class)
            ->add('deliveryDate', DateType::class, [
                'label' => 'Fecha prevista de entrega/recogida',
                'widget' => 'single_text',
                'html5' => true,
                'required' => false,
            ])
            ->add('collectAddress', TextType::class,
                [
                    'label' =>'Drección de recogida',
                    'attr' => ['placeholder' => 'Rellenar en caso de que sea de tipo Recogida']
                ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
