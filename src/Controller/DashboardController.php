<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Needs;
use App\Entity\Place;
use App\Entity\RequestCollect;
use App\Entity\Task;
use App\Entity\User;
use App\Repository\NeedsRepository;
use App\Repository\PlaceRepository;
use App\Repository\RequestCollectRepository;
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
    private $needsRepository;
    private $requestCollectRepository;

    public function __construct(
        TaskRepository $taskRepository,
        UserRepository $userRepository,
        PlaceRepository $placeRepository,
        ThingRepository $thingRepository,
        NeedsRepository $needsRepository,
        RequestCollectRepository $requestCollectRepository
    ) {
        $this->taskRepository = $taskRepository;
        $this->userRepository = $userRepository;
        $this->placeRepository = $placeRepository;
        $this->thingRepository = $thingRepository;
        $this->needsRepository = $needsRepository;
        $this->requestCollectRepository = $requestCollectRepository;
    }

    public function index(Request $request): Response
    {
        if ('POST' === $request->getMethod()) {
            $needs = $request->get('needs', '');
            $collect = $request->get('collect', '');
            if ('' !== $needs) {
                $this->needs($request);
            }
            if ('' !== $collect) {
                $this->collect($request);
            }
        }
        /** @var User $user */
        $user = $this->getUser();

        $done = $this->taskRepository->howManyThingsByStatus(Task::STATUS_DONE);

        $topMakers = $this->userRepository->topMakers();

        $topRequestors = $this->placeRepository->topRequestor();

        $topMaker = \array_shift($topMakers);
        $topRequestor = \array_shift($topRequestors);

        $topNeeded = $this->thingRepository->topNeeded();

        $places = $this->placeRepository->findAll();
        $things = $this->thingRepository->findAll();

        $tasks = [];
        if (null !== $user->maker()) {
            $tasks = $this->taskRepository->findByMakerAndStatus($user->maker(), Task::STATUS_DONE);
        }

        /** @var Task[] $tasksToCollect */
        $tasksToCollect = [];
        if (null !== $user->delivery()) {
            $tasksToCollect = $this->taskRepository->findByStatus(Task::STATUS_COLLECT_REQUESTED);
        }

        return $this->render('dashboard/dashboard.html.twig', [
            'user' => $user,
            'printedThings' => $done,
            'topMaker' => $topMaker,
            'topRequestor' => $topRequestor,
            'topNeeded' => $topNeeded,
            'places' => $places,
            'things' => $things,
            'tasks' => $tasks,
            'tasksToCollect' => $tasksToCollect,
        ]);
    }

    private function needs(Request $request): void
    {
        //SOlicitar material
        $placeId = $request->get('place', '');
        $thingId = $request->get('thing', '');
        $amount = (int) $request->get('amount', 0);

        if (0 === $amount) {
            $this->addFlash(
                    'error',
                    'La cantidad no puede ser 0.'
            );

            return;
        }

        if ('user' === $placeId) {
            /** @var User $user */
            $user = $this->getUser();
            $userPlace = $user->place();
            if (null === $userPlace) {
                $userAddress = $user->address();
                if (null === $userAddress) {
                    $this->addFlash(
                        'error',
                        'Sin poner tu dirección no puedes solicitar material.'
                    );

                    return;
                }
                $placeAddress = new Address();
                $placeAddress->setPostalCode($userAddress->postalCode());
                $placeAddress->setCity($userAddress->city());
                $placeAddress->setAddress1($userAddress->address1());
                $placeAddress->setAddress2($userAddress->address2());
                //Lo damos de alta como demandante
                $userPlace = new Place();
                $userPlace->setAddress($placeAddress);
                $name = 'Anónimo';
                if ('' !== $user->getNick()) {
                    $name = $user->getNick();
                }
                $userPlace->setName($name);
                $this->placeRepository->save($userPlace);
                $user->setPlace($userPlace);
                $this->userRepository->save($user);
                $this->addFlash(
                        'info',
                        'Se te ha dado de alta como demandante, puedes cambiar tu nombre en la sección de Demandantes.'
                );
            }
            $placeId = $userPlace->id();
        }

        $place = $this->placeRepository->find($placeId);
        if (null === $place) {
            $this->addFlash(
                    'error',
                    'Demandante no encontrado :('
            );

            return;
        }
        $thing = $this->thingRepository->find($thingId);
        if (null === $thing) {
            $this->addFlash(
                    'error',
                    'Imprimible no encontrado :('
            );

            return;
        }

        $need = new Needs();
        $need->setThing($thing);
        $need->setPlace($place);
        $need->setAmount($amount);

        $this->needsRepository->save($need);

        $this->addFlash(
                'info',
                'Material solicitado!'
        );
    }

    private function collect(Request $request): void
    {
        //Collect tasks
        $tasksId = $request->get('tasks', '');
        $tasks = [];
        foreach ($tasksId as $taskId) {
            /** @var Task|null $task */
            $task = $this->taskRepository->find($taskId);
            if (null !== $task && $task->amount() > 0) {
                $task->setStatus(Task::STATUS_COLLECT_REQUESTED);
                $tasks[] = $task;
            }
        }

        if (empty($tasks)) {
            $this->addFlash(
                    'warning',
                    'No se ha solicitado porque alguna de las tareas no tenían nada hecho!'
            );

            return;
        }

        $requestCollect = new RequestCollect();
        $requestCollect->setTasks($tasks);
        $requestCollect->setDate(new \DateTime());

        $this->requestCollectRepository->save($requestCollect);

        $this->addFlash(
                'info',
                'Recogida solicitada!'
        );
    }
}
