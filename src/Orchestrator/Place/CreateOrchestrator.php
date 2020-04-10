<?php

namespace App\Orchestrator\Place;

use App\Form\Type\PlaceType;
use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\Entity\Place;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class CreateOrchestrator implements OrchestratorInterface
{
    private $generalRepository;
    private $formFactory;

    public function __construct(GeneralDoctrineRepository $generalDoctrineRepository, FormFactoryInterface $formFactory)
    {
        $this->generalRepository = $generalDoctrineRepository;
        $this->formFactory = $formFactory;
    }

    public function content(Request $request, string $type): array
    {
        $placeId = $request->attributes->get('placeId');

        $place = new Place();
        $hasNeeds = false;
        $create = true;
        if (null !== $placeId) {
            $place = $this->generalRepository->findPlace($placeId);
            $hasNeeds = $place->needs()->count() > 0;
            $create = false;
        }
        $result = [];

        $form = $this->formFactory->create(PlaceType::class, $place);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $place = $form->getData();

            if ($create) {
                $placeInBds = $this->generalRepository->findPlaceByName($place->name());
                if (null === $placeInBds) {
                    $this->generalRepository->savePlace($place);

                    return ['place' => $place];
                }
            } else {
                $this->generalRepository->savePlace($place);

                return ['place' => $place];
            }

            $result = ['error' => 'Nombre de sitio ya existe'];
        }

        $result['form'] = $form->createView();
        $result['hasNeeds'] = $hasNeeds;

        return $result;
    }

    public function canHandleContentOfType(string $type): bool
    {
        $validTypes = ['place-create', 'place-edit'];

        return \in_array($type, $validTypes, true);
    }
}
