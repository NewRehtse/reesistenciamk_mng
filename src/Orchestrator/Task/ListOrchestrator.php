<?php

namespace App\Orchestrator\Task;

use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\Entity\Task;
use App\Persistence\Doctrine\Entity\User;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class ListOrchestrator implements OrchestratorInterface
{
    private $generalRepository;
    private $security;

    public function __construct(
            GeneralDoctrineRepository $generalDoctrineRepository,
        Security $security
    ) {
        $this->generalRepository = $generalDoctrineRepository;
        $this->security = $security;
    }

    public function content(Request $request, string $type): array
    {
        $tasks = [];

        if ($this->security->isGranted('ROLE_ADMIN')) {
            $tasks = $this->generalRepository->getAllTasks();
        }

        if ($this->security->isGranted('ROLE_DELIVERY')) {
            $tasks = $this->generalRepository->findTasksByStatus(Task::STATUS_COLLECT_REQUESTED);
        }

        /** @var User $user */
        $user = $this->security->getUser();
        $maker = $user->maker();

        if (null !== $maker) {
            $tasks = $this->generalRepository->findTasksByMaker($user->maker());
        }

        return [
                'user' => $user,
                'tasks' => $tasks,
        ];
    }

    public function canHandleContentOfType(string $type): bool
    {
        $validTypes = ['task-list'];

        return \in_array($type, $validTypes, true);
    }
}
