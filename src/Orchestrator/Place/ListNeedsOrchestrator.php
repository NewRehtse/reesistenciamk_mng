<?php

namespace App\Orchestrator\Place;

use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use App\Security\PlaceVoter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class ListNeedsOrchestrator implements OrchestratorInterface
{
    /** @var GeneralDoctrineRepository */
    private $generalRepository;

    /** @var Security */
    private $security;

    public function __construct(
            GeneralDoctrineRepository $generalDoctrineRepository,
            Security $security
    ) {
        $this->generalRepository = $generalDoctrineRepository;
        $this->security = $security;
    }

    /**
     * @return array<string, mixed>
     */
    public function content(Request $request, string $type): array
    {
        $placeId = $request->attributes->get('placeId');

        $place = $this->generalRepository->findPlace($placeId);

        if (!$this->security->isGranted(PlaceVoter::LIST_NEEDS, $place)) {
            throw new AccessDeniedException();
        }

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
