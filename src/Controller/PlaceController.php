<?php

namespace App\Controller;

use App\Entity\Needs;
use App\Entity\Place;
use App\Entity\Task;
use App\Form\Type\CoverNeedType;
use App\Form\Type\NeedType;
use App\Form\Type\PlaceType;
use App\Repository\NeedsRepository;
use App\Repository\PlaceRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class PlaceController extends AbstractController
{
    /** @var PlaceRepository */
    private $placeRepository;

    /** @var NeedsRepository */
    private $needsRepository;

    /** @var TaskRepository */
    private $taskRepository;

    public function __construct(
        PlaceRepository $placeRepository,
        NeedsRepository $needRepository,
        TaskRepository $taskRepository
    ) {
        $this->placeRepository = $placeRepository;
        $this->needsRepository = $needRepository;
        $this->taskRepository = $taskRepository;
    }

    public function list(): Response
    {
        $places = $this->placeRepository->findAll();

        return $this->render('places/list.html.twig', ['places' => $places]);
    }

    public function create(Request $request): Response
    {
        $place = new Place();

        $form = $this->createForm(PlaceType::class, $place);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $place = $form->getData();

            $this->placeRepository->save($place);

            return $this->redirectToRoute('places');
        }

        return $this->render('places/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function update(Request $request, int $placeId): Response
    {
//        if (!$this->isGranted('ROLE_ADMIN')) {
//            return $this->redirect('/places');
//        }

        $place = $this->placeRepository->find($placeId);

        $form = $this->createForm(PlaceType::class, $place);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $place = $form->getData();

            $this->placeRepository->save($place);

            return $this->redirectToRoute('places');
        }

        return $this->render('places/update.html.twig', [
            'form' => $form->createView(),
            'hasNeeds' => $place->needs()->count() > 0,
        ]);
    }

    public function delete(Request $request, int $placeId): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirect('/places');
        }

        $place = $this->placeRepository->find($placeId);

        if (null === $place) {
            return $this->redirectToRoute('places');
        }

        if ($place->needs()->count() > 0) {
            return $this->redirectToRoute('places');
        }

        $this->placeRepository->delete($place);

        return $this->redirectToRoute('places');
    }

    public function addNeeds(Request $request, int $placeId): Response
    {
//        if (!$this->isGranted('ROLE_ADMIN')) {
//            return $this->redirect('/places');
//        }

        $place = $this->placeRepository->find($placeId);
        $need = new Needs();
        $need->setPlace($place);

        $form = $this->createForm(NeedType::class, $need);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Needs $currentNeed */
            $currentNeed = $form->getData();

            if (null === $currentNeed->thing() || null === $currentNeed->place()) {
                return $this->redirectToRoute('places');
            }

            $thing = $currentNeed->thing();
            $place = $currentNeed->place();
            /** @var Needs|null $need */
            $need = $this->needsRepository->findOneBy(['thing' => $thing, 'place' => $place]);

            if (null !== $need) {
                $need->setAmount($need->amount() + $currentNeed->amount());
                $this->needsRepository->save($need);
            } else {
                $this->needsRepository->save($currentNeed);
            }

            return $this->redirectToRoute('places.needs.list', ['placeId' => $placeId]);
        }

        return $this->render('places/addNeeds.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function needs(Request $request, int $placeId): Response
    {
        $place = $this->placeRepository->find($placeId);
        $needs = $this->needsRepository->findBy(['place' => $placeId]);

        $needsResult = [];
        foreach ($needs as $need) {
            $collectedOrDelivered = $this->taskRepository->howManyThingsDelivered($need->thing());
            $needsResult[] = ['need' => $need, 'collectedOrDelivered' => $collectedOrDelivered];
        }

        return $this->render('places/needs_list.html.twig', ['needs' => $needsResult, 'place' => $place]);
    }

    public function coverNeed(Request $request, int $placeId, int $needId): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirect('/places');
        }

        $need = $this->needsRepository->find($needId);
        if (null === $need) {
            return $this->redirectToRoute('places.needs.list', ['placeId' => $placeId]);
        }

        $form = $this->createForm(CoverNeedType::class, $need);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->needsRepository->save($need);

            return $this->redirectToRoute('places.needs.list', ['placeId' => $placeId]);
        }

        return $this->render('places/addNeeds.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
