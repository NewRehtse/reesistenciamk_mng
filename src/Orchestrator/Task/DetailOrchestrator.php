<?php

namespace App\Orchestrator\Task;

use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use App\Security\TaskVoter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class DetailOrchestrator implements OrchestratorInterface
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

        if (!$this->security->isGranted(TaskVoter::VIEW, $task)) {
            throw new AccessDeniedException();
        }

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
