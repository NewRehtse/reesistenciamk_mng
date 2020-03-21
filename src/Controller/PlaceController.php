<?php

namespace App\Controller;

use App\Entity\Needs;
use App\Entity\Place;
use App\Form\Type\NeedType;
use App\Form\Type\PlaceType;
use App\Repository\NeedsRepository;
use App\Repository\PlaceRepository;
use App\Repository\ThingRepository;
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

    /** @var ThingRepository */
    private $thingRepository;

    public function __construct(PlaceRepository $placeRepository, NeedsRepository $needRepository, ThingRepository $thingRepository)
    {
        $this->placeRepository = $placeRepository;
        $this->needsRepository = $needRepository;
        $this->thingRepository = $thingRepository;
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
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirect('/places');
        }

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
        ]);
    }

    public function delete(Request $request, int $placeId): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirect('/places');
        }

        $place = $this->placeRepository->find($placeId);

        if (null !== $place) {
            $this->placeRepository->delete($place);
        }

        return $this->redirectToRoute('places');
    }

    public function addNeeds(Request $request, int $placeId): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirect('/places');
        }

        $need = new Needs();

        $form = $this->createForm(NeedType::class, $need);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Needs $need */
            $need = $form->getData();

            if (null === $need->thing() || null === $need->place()) {
                return $this->redirectToRoute('places');
            }

            //No tengo claro que esto haga algo util TODO
            $thing = $this->thingRepository->find($need->thing()->id());
            $place = $this->placeRepository->find($need->place()->id());

            if (null === $thing) {
                return $this->redirectToRoute('places');
            }
            if (null === $place) {
                return $this->redirectToRoute('places');
            }

            $need->setPlace($place);
            $need->setThing($thing);

            $this->needsRepository->save($need);

            return $this->redirectToRoute('places');
        }

        return $this->render('places/addNeeds.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function needs(Request $request, int $placeId): Response
    {
        $place = $this->placeRepository->find($placeId);
        $needs = $this->needsRepository->findBy(['place' => $placeId]);

        return $this->render('places/needs_list.html.twig', ['needs' => $needs, 'place' => $place]);
    }
}
