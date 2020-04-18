<?php

namespace App\Orchestrator\Thing;

use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\Entity\Task;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use App\Security\ThingVoter;
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
        if (!$this->security->isGranted(ThingVoter::LIST)) {
            throw new AccessDeniedException();
        }

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
