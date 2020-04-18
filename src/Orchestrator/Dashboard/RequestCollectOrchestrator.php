<?php

namespace App\Orchestrator\Dashboard;

use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\Entity\RequestCollect;
use App\Persistence\Doctrine\Entity\Task;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use Symfony\Component\HttpFoundation\Request;

class RequestCollectOrchestrator implements OrchestratorInterface
{
    /** @var GeneralDoctrineRepository */
    private $generalRepository;

    public function __construct(GeneralDoctrineRepository $generalDoctrineRepository)
    {
        $this->generalRepository = $generalDoctrineRepository;
    }

    /**
     * @inheritDoc
     */
    public function content(Request $request, string $type): array
    {
        //Collect tasks
        $tasksId = $request->get('tasks', '');
        $tasks = [];
        foreach ($tasksId as $taskId) {
            /** @var Task|null $task */
            $task = $this->generalRepository->findTask($taskId);
            if (null !== $task && $task->amount() > 0) {
                $task->setStatus(Task::STATUS_COLLECT_REQUESTED);
                $tasks[] = $task;
            }
        }

        if (empty($tasks)) {
            throw new \InvalidArgumentException('Alguna de las tareas tiene 0 imprimibles hechos.');
        }

        $requestCollect = new RequestCollect();
        $requestCollect->setTasks($tasks);
        $requestCollect->setDate(new \DateTime());

        $this->generalRepository->saveRequestCollect($requestCollect);

        return [
                'type' => 'info',
                'message' => 'Recogida solicitada!',
        ];
    }

    /**
     * @inheritDoc
     */
    public function canHandleContentOfType(string $type): bool
    {
        $validTypes = ['dashboard-request-collect'];

        return \in_array($type, $validTypes, true);
    }
}
