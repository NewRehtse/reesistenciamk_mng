<?php

namespace App\Controller;

use App\Entity\Place;
use App\Form\Type\PlaceType;
use App\Repository\PlaceRepository;
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

    public function __construct(PlaceRepository $placeRepository)
    {
        $this->placeRepository = $placeRepository;
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
}
