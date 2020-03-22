<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Form\Type\TaskType;
use App\Repository\NeedsRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class TaskController extends AbstractController
{
    /** @var NeedsRepository */
    private $needsRepository;

    /** @var TaskRepository */
    private $taskRepository;

    /** @var UserRepository */
    private $userRepository;

    public function __construct(
        TaskRepository $taskRepository,
        NeedsRepository $needRepository,
        UserRepository $userRepository
    ) {
        $this->taskRepository = $taskRepository;
        $this->needsRepository = $needRepository;
        $this->userRepository = $userRepository;
    }

    public function list(): Response
    {
        $tasks = $this->getTasks();

        return $this->render('tasks/list.html.twig', ['tasks' => $tasks]);
    }

    /**
     * @return Task[]
     */
    private function getTasks(): array
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->taskRepository->findAll();
        }

        if ($this->isGranted('ROLE_DELIVERY')) {
            return $this->taskRepository->findBy(['status' => Task::STATUS_DONE, 'deliveryType' => Task::DELIVER_TYPE_COLLECT]);
        }

        $user = $this->getCurrentUser();
        if (null === $user) {
            return [];
        }

        return $this->taskRepository->findBy(['maker' => $user]);
    }

    public function create(Request $request): Response
    {
        $task = new Task();
        $user = $this->getCurrentUser();
        if (null !== $user) {
            $task->setMaker($user);
        }

        return $this->handleTask($request, $task);
    }

    public function edit(Request $request, int $taskId): Response
    {
        $task = $this->taskRepository->find($taskId);
        if (null === $task) {
            return $this->redirect('/tasks');
        }

        return $this->handleTask($request, $task);
    }

    public function createFromNeed(Request $request, int $needId): Response
    {
        $task = new Task();
        $need = $this->needsRepository->find($needId);
        if (null !== $need) {
            $task->setPlace($need->place());
            $task->setThing($need->thing());
            $task->setAmount($need->amount());
        }
        $user = $this->getCurrentUser();
        if (null !== $user) {
            $task->setMaker($user);
        }

        return $this->handleTask($request, $task);
    }

    private function handleTask(Request $request, Task $task): Response
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            $this->taskRepository->save($task);

            return $this->redirectToRoute('places');
        }

        return $this->render('tasks/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function getCurrentUser(): ?User
    {
        $usr = $this->getUser();
        if (null === $usr) {
            return null;
        }

        return $this->userRepository->findOneBy(['email' => $usr->getUsername()]);
    }
}
