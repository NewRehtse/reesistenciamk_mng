<?php

namespace App\Orchestrator\Dashboard;

use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\Entity\Task;
use App\Persistence\Doctrine\Entity\User;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class IndexOrchestrator implements OrchestratorInterface
{
    /** @var Security */
    private $security;

    /** @var GeneralDoctrineRepository */
    private $generalRepository;

    public function __construct(
        GeneralDoctrineRepository $generalDoctrineRepository,
        Security $security
    ) {
        $this->security = $security;
        $this->generalRepository = $generalDoctrineRepository;
    }

    /**
     * @inheritDoc
     */
    public function content(Request $request, string $type): array
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $done = $this->generalRepository->howManyThingsByStatus(Task::STATUS_DONE);

        $topMakers = $this->generalRepository->topMakers();

        $topRequestors = $this->generalRepository->topRequestor();

        $topMaker = \array_shift($topMakers);
        $topRequestor = \array_shift($topRequestors);

        $topNeeded = $this->generalRepository->topNeeded();

        $places = $this->generalRepository->getAllPlaces();
        $things = $this->generalRepository->getAllThings();

        $tasks = [];
        if (null !== $user->maker()) {
            $tasks = $this->generalRepository->findTasksByMakerAndStatus($user->maker(), Task::STATUS_DONE);
        }

        /** @var Task[] $tasksToCollect */
        $tasksToCollect = [];
        if (null !== $user->delivery()) {
            $tasksToCollect = $this->generalRepository->findTasksByStatus(Task::STATUS_COLLECT_REQUESTED);
        }

        return [
                'user' => $user,
                'printedThings' => $done,
                'topMaker' => $topMaker,
                'topRequestor' => $topRequestor,
                'topNeeded' => $topNeeded,
                'places' => $places,
                'things' => $things,
                'tasks' => $tasks,
                'tasksToCollect' => $tasksToCollect,
        ];
    }

    /**
     * @inheritDoc
     */
    public function canHandleContentOfType(string $type): bool
    {
        $validTypes = ['dashboard-index'];

        return \in_array($type, $validTypes, true);
    }
}
