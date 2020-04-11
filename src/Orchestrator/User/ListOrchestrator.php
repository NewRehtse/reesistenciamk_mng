<?php

namespace App\Orchestrator\User;

use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class ListOrchestrator implements OrchestratorInterface
{
    /** @var GeneralDoctrineRepository */
    private $generalRepository;

    public function __construct(GeneralDoctrineRepository $generalDoctrineRepository)
    {
        $this->generalRepository = $generalDoctrineRepository;
    }

    /**
     * @return array<string, mixed>
     */
    public function content(Request $request, string $type): array
    {
        $users = $this->generalRepository->getAllUsers();

        return ['users' => $users];
    }

    public function canHandleContentOfType(string $type): bool
    {
        $validTypes = ['user-list'];

        return \in_array($type, $validTypes, true);
    }
}
