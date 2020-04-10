<?php

namespace App\Orchestrator\Task;

use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class DetailOrchestrator implements OrchestratorInterface
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
            return [];
        }

        $serialNumbers = $this->generalRepository->findTaskSerialNumbers($task);

        return [
                'task' => $task,
                'serialNumbers' => $serialNumbers,
        ];
    }

    public function canHandleContentOfType(string $type): bool
    {
        $validTypes = ['task-detail'];

        return \in_array($type, $validTypes, true);
    }
}
