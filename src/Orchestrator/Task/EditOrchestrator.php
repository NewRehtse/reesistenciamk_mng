<?php

namespace App\Orchestrator\Task;

use App\Form\Type\TaskUpdateType;
use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\Entity\Task;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use App\Security\TaskVoter;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class EditOrchestrator implements OrchestratorInterface
{
    /** @var GeneralDoctrineRepository */
    private $generalRepository;

    /** @var Security */
    private $security;

    /** @var FormFactoryInterface */
    private $formFactory;

    public function __construct(
            GeneralDoctrineRepository $generalDoctrineRepository,
            FormFactoryInterface $formFactory,
            Security $security
    ) {
        $this->generalRepository = $generalDoctrineRepository;
        $this->security = $security;
        $this->formFactory = $formFactory;
    }

    /**
     * @return array<string, mixed>
     */
    public function content(Request $request, string $type): array
    {
        $task = $this->getTask($request);

        if (!$this->security->isGranted(TaskVoter::EDIT, $task)) {
            throw new AccessDeniedException();
        }

        $form = $this->formFactory->create(TaskUpdateType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Task $task */
            $task = $form->getData();

            if (0 === $task->amount()) {
                throw new \InvalidArgumentException('No puedes crear un lote con 0 imprimibles... ');
            }

            $this->generalRepository->saveTask($task);

            return ['taskId' => $task->id()];
        }

        return ['form' => $form->createView()];
    }

    private function getTask(Request $request): Task
    {
        $taskId = $request->attributes->get('taskId', null);

        return $this->generalRepository->findTask($taskId);
    }

    public function canHandleContentOfType(string $type): bool
    {
        $validTypes = ['task-edit'];

        return \in_array($type, $validTypes, true);
    }
}
