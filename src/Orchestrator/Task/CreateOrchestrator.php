<?php

namespace App\Orchestrator\Task;

use App\Form\Type\TaskType;
use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\Entity\Address;
use App\Persistence\Doctrine\Entity\Needs;
use App\Persistence\Doctrine\Entity\Task;
use App\Persistence\Doctrine\Entity\User;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class CreateOrchestrator implements OrchestratorInterface
{
    private $generalRepository;
    private $security;
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

    public function content(Request $request, string $type): array
    {
        $task = $this->getTask($request);

        $form = $this->formFactory->create(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Task $task */
            $task = $form->getData();
            if (0 === $task->amount()) {
                throw new \InvalidArgumentException('No puedes crear un lote con 0 imprimibles... ');
            }

            $this->generalRepository->createTaskAndSerialNumbers($task);

            return ['taskId' => $task->id()];
        }

        return ['form' => $form->createView()];
    }

    private function getTask(Request $request): Task
    {
        $taskId = $request->attributes->get('taskId', null);
        $needId = $request->attributes->get('needId', null);

        $needs = null;
        if (null !== $needId) {
            $needs = $this->generalRepository->findNeeds($needId);
        }

        if (null === $taskId) {
            return $this->createTask($needs);
        }

        return $this->generalRepository->findTask($taskId);
    }

    private function createTask(?Needs $needs): Task
    {
        $task = new Task();
        if (null !== $needs) {
            if (null !== $needs) {
                $task->setPlace($needs->place());
                $task->setThing($needs->thing());
                $task->setAmount($needs->amount());
            }
        }

        /** @var User $user */
        $user = $this->security->getUser();
        if (null !== $user && null !== $user->maker()) {
            $task->setMaker($user->maker());
        }

        $userAddress = $user->address();
        if (null !== $userAddress) {
            $taskAddress = new Address();
            $taskAddress->setPostalCode($userAddress->postalCode());
            $taskAddress->setCity($userAddress->city());
            $taskAddress->setAddress1($userAddress->address1());
            $taskAddress->setAddress2($userAddress->address2());
            $task->setCollectAddress($taskAddress);
        }

        return $task;
    }

    public function canHandleContentOfType(string $type): bool
    {
        $validTypes = ['task-create'];

        return \in_array($type, $validTypes, true);
    }
}
