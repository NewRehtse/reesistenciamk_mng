<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\Thing;
use App\Form\Type\ThingType;
use App\Repository\TaskRepository;
use App\Repository\ThingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class ThingController extends AbstractController
{
    /** @var ThingRepository */
    private $thingRepository;

    /** @var TaskRepository */
    private $taskRepository;

    public function __construct(ThingRepository $thingRepository, TaskRepository $taskRepository)
    {
        $this->thingRepository = $thingRepository;
        $this->taskRepository = $taskRepository;
    }

    public function list(): Response
    {
        $things = $this->thingRepository->findAll();

        $result = [];
        foreach ($things as $thing) {
            $collected = $this->taskRepository->howManyThingsByStatus(Task::STATUS_COLLECTED);
            $delivered = $this->taskRepository->howManyThingsByStatus(Task::STATUS_DELIVERED);
            $done = $this->taskRepository->howManyThingsByStatus(Task::STATUS_DONE);
            $result[] = ['thing' => $thing, 'delivered' => $delivered, 'collected' => $collected, 'done' => $done];
        }

        return $this->render('things/list.html.twig', ['results' => $result]);
    }

    public function create(Request $request): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirect('/things');
        }

        $thing = new Thing();

        $form = $this->createForm(ThingType::class, $thing);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $thing = $form->getData();

            $this->thingRepository->save($thing);

            return $this->redirectToRoute('things');
        }

        return $this->render('things/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function update(Request $request, int $thingId): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirect('/things');
        }

        $thing = $this->thingRepository->find($thingId);

        $form = $this->createForm(ThingType::class, $thing);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $thing = $form->getData();

            $this->thingRepository->save($thing);

            return $this->redirectToRoute('things');
        }

        return $this->render('things/update.html.twig', [
            'form' => $form->createView(),
            'hasTasks' => $thing->tasks()->count() > 0,
        ]);
    }

    public function delete(Request $request, int $thingId): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirect('/things');
        }

        $thing = $this->thingRepository->find($thingId);

        if (null === $thing) {
            return $this->redirectToRoute('things');
        }
        if ($thing->tasks()->count() > 0) {
            return $this->redirectToRoute('things');
        }

        $this->thingRepository->delete($thing);

        return $this->redirectToRoute('things');
    }
}
