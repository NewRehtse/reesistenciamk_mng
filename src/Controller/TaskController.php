<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Task;
use App\Entity\User;
use App\Form\Type\TaskType;
use App\Form\Type\TaskUpdateType;
use App\Repository\NeedsRepository;
use App\Repository\SerialNumberRepository;
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

    /** @var SerialNumberRepository */
    private $serialNumberRepository;

    public function __construct(
        TaskRepository $taskRepository,
        NeedsRepository $needRepository,
        UserRepository $userRepository,
        SerialNumberRepository $serialNumberRepository
    ) {
        $this->taskRepository = $taskRepository;
        $this->needsRepository = $needRepository;
        $this->userRepository = $userRepository;
        $this->serialNumberRepository = $serialNumberRepository;
    }

    public function list(): Response
    {
        $tasks = $this->getTasks();

        /** @var User $user */
        $user = $this->getUser();

        return $this->render('tasks/list.html.twig', [
            'user' => $user,
            'tasks' => $tasks,
        ]);
    }

    public function detail(Request $request, int $taskId): Response
    {
        $task = $this->taskRepository->find($taskId);

        if (null === $task) {
            return $this->redirectToRoute('tasks');
        }

        $serialNumbers = $this->serialNumberRepository->findBy(['task' => $task]);

        return $this->render('tasks/detail.html.twig', [
            'task' => $task,
            'serialNumbers' => $serialNumbers,
        ]);
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
            return $this->taskRepository->findBy(['status' => Task::STATUS_COLLECT_REQUESTED]);
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

        return $this->handleCreateTaskForm($request, $task);
    }

    public function edit(Request $request, int $taskId): Response
    {
        $task = $this->taskRepository->find($taskId);
        if (null === $task) {
            return $this->redirect('/tasks');
        }

        $form = $this->createForm(TaskUpdateType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            $this->taskRepository->save($task);

            return $this->redirectToRoute('tasks');
        }

        return $this->render('tasks/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function updateStatus(Request $request, int $taskId): Response
    {
        $task = $this->taskRepository->find($taskId);
        if (null === $task) {
            return $this->redirect('/tasks');
        }
        $status = $request->get('status', '');
        if ('collected' === $status) {
            $task->setStatus(Task::STATUS_COLLECTED);
            $this->taskRepository->save($task);
        }

        $this->addFlash(
                'info',
                'Material recogido!'
        );

        return $this->redirectToRoute('home');
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

        return $this->handleCreateTaskForm($request, $task);
    }

    public function handleCreateTaskForm(Request $request, Task $task)
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Task $task */
            $task = $form->getData();
            if (0 === $task->amount()) {
                $this->addFlash(
                        'warning',
                        'No puedes crear un lote con 0 imprimibles... '
                );

                return $this->redirectToRoute('tasks', []);
            }

            $this->taskRepository->save($task);

            //GENERATE S/N
            $this->serialNumberRepository->createSerialNumers($task);

            return $this->redirectToRoute('task.detail', ['taskId' => $task->id()]);
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
