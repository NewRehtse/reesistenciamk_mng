<?php

namespace App\Orchestrator\Task;

use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\Entity\Task;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class UpdateStatusOrchestrator implements OrchestratorInterface
{
    private $generalRepository;

    public function __construct(GeneralDoctrineRepository $generalDoctrineRepository)
    {
        $this->generalRepository = $generalDoctrineRepository;
    }

    public function content(Request $request, string $type): array
    {
        $taskId = $request->attributes->get('taskId');

        $task = $this->generalRepository->findTask($taskId);

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
