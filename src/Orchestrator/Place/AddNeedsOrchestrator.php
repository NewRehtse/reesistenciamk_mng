<?php

namespace App\Orchestrator\Place;

use App\Form\Type\NeedType;
use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\Entity\Needs;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use App\Security\PlaceVoter;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class AddNeedsOrchestrator implements OrchestratorInterface
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
        $placeId = $request->attributes->get('placeId');

        $place = $this->generalRepository->findPlace($placeId);

        if (!$this->security->isGranted(PlaceVoter::ADD_NEED, $place)) {
            throw new AccessDeniedException();
        }

        $need = new Needs();

        if (null === $place) {
            throw new NotFoundHttpException('No se ha encontrado el demandante.');
        }

        $need->setPlace($place);

        $form = $this->formFactory->create(NeedType::class, $need);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Needs $currentNeed */
            $currentNeed = $form->getData();

            $thing = $currentNeed->thing();
            $place = $currentNeed->place();

            if (null === $thing || null === $place) {
                throw new NotFoundHttpException('No se ha encontrado imprimible o demandante.');
            }

            /** @var Needs|null $need */
            $need = $this->generalRepository->findNeedsByThingAndPlace($thing, $place);

            if ($this->security->isGranted(PlaceVoter::ADD_VALID_NEED)) {
                $need->validate();
            }

            if (null !== $need) {
                $need->setAmount($need->amount() + $currentNeed->amount());
                $this->generalRepository->saveNeeds($need);
            } else {
                $this->generalRepository->saveNeeds($currentNeed);
            }

            return ['placeId' => $placeId];
        }

        return ['form' => $form->createView()];
    }

    public function canHandleContentOfType(string $type): bool
    {
        $validTypes = ['place-add-needs'];

        return \in_array($type, $validTypes, true);
    }
}
