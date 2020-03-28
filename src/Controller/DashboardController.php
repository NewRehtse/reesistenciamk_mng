<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\PlaceRepository;
use App\Repository\TaskRepository;
use App\Repository\ThingRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class DashboardController extends AbstractController
{
    private $taskRepository;
    private $userRepository;
    private $placeRepository;
    private $thingRepository;

    public function __construct(
            TaskRepository $taskRepository,
            UserRepository $userRepository,
            PlaceRepository $placeRepository,
            ThingRepository $thingRepository
    ) {
        $this->taskRepository = $taskRepository;
        $this->userRepository = $userRepository;
        $this->placeRepository = $placeRepository;
        $this->thingRepository = $thingRepository;
    }

    public function index(Request $request): Response
    {
        $done = $this->taskRepository->howManyThingsByStatus(Task::STATUS_DONE);

        $topMakers = $this->userRepository->topMakers();

        $topRequestors = $this->placeRepository->topRequestor();

        $topMaker = \array_shift($topMakers);
        $topRequestor = \array_shift($topRequestors);

        $topNeeded = $this->thingRepository->topNeeded();

        return $this->render('dashboard/dashboard.html.twig', [
            'printedThings' => $done,
            'topMaker' => $topMaker,
            'topRequestor' => $topRequestor,
            'topNeeded' => $topNeeded,
        ]);
    }
}
