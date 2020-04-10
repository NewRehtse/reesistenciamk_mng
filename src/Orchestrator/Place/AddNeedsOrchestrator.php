<?php

namespace App\Orchestrator\Place;

use App\Form\Type\NeedType;
use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\Entity\Needs;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class AddNeedsOrchestrator implements OrchestratorInterface
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

        $place = $this->generalRepository->findPlace($placeId);

        $need = new Needs();
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
