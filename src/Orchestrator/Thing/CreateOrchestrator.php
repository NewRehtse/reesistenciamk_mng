<?php

namespace App\Orchestrator\Thing;

use App\Form\Type\ThingType;
use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\Entity\Thing;
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

    public function __construct(
            GeneralDoctrineRepository $generalDoctrineRepository,
            FormFactoryInterface $formFactory
    ) {
        $this->generalRepository = $generalDoctrineRepository;
        $this->formFactory = $formFactory;
    }

    public function content(Request $request, string $type): array
    {
        $thingId = $request->attributes->get('thingId');

        $thing = new Thing();
        if (null !== $thingId) {
            $thing = $this->generalRepository->findThing($thingId);
        }

        $form = $this->formFactory->create(ThingType::class, $thing);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $thing = $form->getData();

            $this->generalRepository->saveThing($thing);

            return ['thingId' => $thing->id()];
        }

        return [
                'form' => $form->createView(),
            'hasTasks' => $thing->tasks()->count() > 0,
        ];
    }

    public function canHandleContentOfType(string $type): bool
    {
        $validTypes = ['thing-create', 'thing-update'];

        return \in_array($type, $validTypes, true);
    }
}
