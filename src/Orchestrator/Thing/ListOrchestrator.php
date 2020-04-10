<?php

namespace App\Orchestrator\Thing;

use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\Entity\Task;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class ListOrchestrator implements OrchestratorInterface
{
    private $generalRepository;

    public function __construct(GeneralDoctrineRepository $generalDoctrineRepository)
    {
        $this->generalRepository = $generalDoctrineRepository;
    }

    public function content(Request $request, string $type): array
    {
        $things = $this->generalRepository->getAllThings();

        $result = [];
        foreach ($things as $thing) {
            $collected = $this->generalRepository->howManyThingsByIdAndStatus($thing, Task::STATUS_COLLECTED);
            $delivered = $this->generalRepository->howManyThingsByIdAndStatus($thing, Task::STATUS_DELIVERED);
            $done = $this->generalRepository->howManyThingsByIdAndStatus($thing, Task::STATUS_DONE);

            $result[] = ['thing' => $thing, 'delivered' => $delivered, 'collected' => $collected, 'done' => $done];
        }

        return ['results' => $result];
    }

    public function canHandleContentOfType(string $type): bool
    {
        $validTypes = ['thing-list'];

        return \in_array($type, $validTypes, true);
    }
}
