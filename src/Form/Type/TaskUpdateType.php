<?php

namespace App\Form\Type;

use App\Persistence\Doctrine\Entity\Place;
use App\Persistence\Doctrine\Entity\Task;
use App\Persistence\Doctrine\Repository\PlaceRepository;
use App\Persistence\Doctrine\Repository\ThingRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class TaskUpdateType extends AbstractType
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
        $defaultPlaces = ['Sin sitio' => null];
        $places = $this->placeRepository->findAll();
        $places = \array_merge($defaultPlaces, $places);
        $things = $this->thingRepository->findAll();
        $deliveryTypes = [
            Task::GetDeliveryTypeText(Task::DELIVER_TYPE_UNDEFINED) => Task::DELIVER_TYPE_UNDEFINED,
            Task::GetDeliveryTypeText(Task::DELIVER_TYPE_COLLECT) => Task::DELIVER_TYPE_COLLECT,
            Task::GetDeliveryTypeText(Task::DELIVER_TYPE_DELIVER) => Task::DELIVER_TYPE_DELIVER,
        ];

        $status = [
            Task::GetStatusText(Task::STATUS_DONE) => Task::STATUS_DONE,
            Task::GetStatusText(Task::STATUS_DELIVERED) => Task::STATUS_DELIVERED,
            Task::GetStatusText(Task::STATUS_COLLECTED) => Task:: STATUS_COLLECTED,
        ];

        $builder
            ->add('status', ChoiceType::class, ['choices' => $status, 'label' => 'Estado actual'])
            ->add('deliveryType', ChoiceType::class, ['label' => 'Tipo de entrega', 'choices' => $deliveryTypes])
            ->add('collectAddress', AddressType::class,
                [
                    'label' => 'Drección de recogida',
                    'attr' => ['placeholder' => 'Rellenar en caso de que sea de tipo Recogida'],
                    'required' => false,
                ])
            ->add('place', ChoiceType::class, [
                'label' => 'Sitio. Rellenar en caso de que sea de tipo Entrega',
                'required' => false,
                'choices' => $places,
                'choice_value' => function (?Place $place = null) {
                    return $place ? $place->id() : null;
                },
                'choice_label' => function (?Place $choice, $key, $value) {
                    if (null === $choice) {
                        return 'Sin sitio';
                    }

                    return $choice->name();
                },
            ])
            ->add('extra', TextType::class, ['required' => false, 'label' => 'Otra información'])
            ->add('save', SubmitType::class, ['label' => 'Guardar'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
