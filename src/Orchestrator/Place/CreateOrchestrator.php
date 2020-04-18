<?php

namespace App\Orchestrator\Place;

use App\Form\Type\PlaceType;
use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\Entity\Place;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use App\Security\PlaceVoter;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class CreateOrchestrator implements OrchestratorInterface
{
    /** @var GeneralDoctrineRepository */
    private $generalRepository;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var Security */
    private $security;

    public function __construct(
            GeneralDoctrineRepository $generalDoctrineRepository,
            FormFactoryInterface $formFactory,
            Security $security
    ) {
        $this->generalRepository = $generalDoctrineRepository;
        $this->formFactory = $formFactory;
        $this->security = $security;
    }

    /**
     * @return array<string, mixed>
     */
    public function content(Request $request, string $type): array
    {
        if (!$this->security->isGranted(PlaceVoter::CREATE)) {
            throw new AccessDeniedException();
        }

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
                if ($this->security->isGranted(PlaceVoter::CREATE_VALID_PLACES)) {
                    $place->validate();
                }

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
        $result['place'] = $place;

        return $result;
    }

    public function canHandleContentOfType(string $type): bool
    {
        $validTypes = ['place-create', 'place-edit'];

        return \in_array($type, $validTypes, true);
    }
}
