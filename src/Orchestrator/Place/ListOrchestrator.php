<?php

namespace App\Orchestrator\Place;

use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use App\Security\PlaceVoter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class ListOrchestrator implements OrchestratorInterface
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
        if (!$this->security->isGranted(PlaceVoter::LIST)) {
            throw new AccessDeniedException();
        }

        $places = $this->generalRepository->getAllPlaces();

        return ['places' => $places];
    }

    public function canHandleContentOfType(string $type): bool
    {
        $validTypes = ['place-list'];

        return \in_array($type, $validTypes, true);
    }
}
