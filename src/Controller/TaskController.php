<?php

namespace App\Controller;

use App\Entity\Task;
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

    public function createFromNeed(Request $request, int $needId): Response
    {
        $task = new Task();
        $need = $this->needsRepository->find($needId);
        if (null !== $need) {
            $task->setPlace($need->place());
            $task->setThing($need->thing());
            $task->setAmount($need->amount());
        }
        $usr = $this->getUser();
        if (null !== $usr) {
            $user = $this->userRepository->findOneBy(['email' => $usr->getUsername()]);
            $task->setMaker($user);
        }

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();

            $this->taskRepository->save($task);

            return $this->redirectToRoute('places');
        }

        return $this->render('tasks/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
