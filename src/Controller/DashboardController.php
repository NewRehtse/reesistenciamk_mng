<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Needs;
use App\Entity\Place;
use App\Entity\Task;
use App\Entity\User;
use App\Repository\NeedsRepository;
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
    private $needsRepository;

    public function __construct(
            TaskRepository $taskRepository,
            UserRepository $userRepository,
            PlaceRepository $placeRepository,
            ThingRepository $thingRepository,
            NeedsRepository $needsRepository
    ) {
        $this->taskRepository = $taskRepository;
        $this->userRepository = $userRepository;
        $this->placeRepository = $placeRepository;
        $this->thingRepository = $thingRepository;
        $this->needsRepository = $needsRepository;
    }

    public function index(Request $request): Response
    {
        if ('POST' === $request->getMethod()) {
            $needs = $request->get('needs', '');
            if ('' !== $needs) {
                $this->needs($request);
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
            $tasks = $this->taskRepository->findByMaker($user->maker());
        }

        return $this->render('dashboard/dashboard.html.twig', [
                'printedThings' => $done,
                'topMaker' => $topMaker,
                'topRequestor' => $topRequestor,
                'topNeeded' => $topNeeded,
                'user' => $user,
                'places' => $places,
                'things' => $things,
                'tasks' => $tasks,
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
}
