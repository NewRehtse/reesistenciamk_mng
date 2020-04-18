<?php

namespace App\Orchestrator\Task;

use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\Entity\Task;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use App\Security\TaskVoter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class UpdateStatusOrchestrator implements OrchestratorInterface
{
    /** @var GeneralDoctrineRepository */
    private $generalRepository;

    /** @var Security */
    private $security;

    public function __construct(GeneralDoctrineRepository $generalDoctrineRepository, Security $security)
    {
        $this->generalRepository = $generalDoctrineRepository;
        $this->security = $security;
    }

    /**
     * @return array<string, mixed>
     */
    public function content(Request $request, string $type): array
    {
        $taskId = $request->attributes->get('taskId');

        $task = $this->generalRepository->findTask($taskId);

        if (!$this->security->isGranted(TaskVoter::EDIT, $task)) {
            throw new AccessDeniedException();
        }

        if (null === $task) {
            throw new NotFoundHttpException('Lote no encontrado');
        }

        $status = $request->get('status', '');

        if ('collected' === $status) {
            $task->setStatus(Task::STATUS_COLLECTED);
            $this->generalRepository->saveTask($task);
        }

        return [];
    }

    public function canHandleContentOfType(string $type): bool
    {
        $validTypes = ['task-update-status'];

        return \in_array($type, $validTypes, true);
    }
}
