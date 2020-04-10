<?php

namespace App\Orchestrator\Place;

use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class ListNeedsOrchestrator implements OrchestratorInterface
{
    private $generalRepository;

    public function __construct(GeneralDoctrineRepository $generalDoctrineRepository)
    {
        $this->generalRepository = $generalDoctrineRepository;
    }

    public function content(Request $request, string $type): array
    {
        $placeId = $request->attributes->get('placeId');

        $place = $this->generalRepository->findPlace($placeId);
        $needs = $this->generalRepository->findNeedsByPlace($place);

        $needsResult = [];
        foreach ($needs as $need) {
            $collectedOrDelivered = $this->generalRepository->howManyThingsDelivered($need->thing());
            $needsResult[] = ['need' => $need, 'collectedOrDelivered' => $collectedOrDelivered];
        }

        return ['needs' => $needsResult, 'place' => $place];
    }

    public function canHandleContentOfType(string $type): bool
    {
        $validTypes = ['place-list-needs'];

        return \in_array($type, $validTypes, true);
    }
}
