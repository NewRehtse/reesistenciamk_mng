<?php

namespace App\Orchestrator\Task;

use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\Entity\Task;
use App\Persistence\Doctrine\Entity\User;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use App\Security\TaskVoter;
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
        if (!$this->security->isGranted(TaskVoter::LIST)) {
            throw new AccessDeniedException();
        }

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
