<?php

namespace App\Controller;

use App\Entity\Thing;
use App\Form\Type\ThingType;
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

    public function __construct(ThingRepository $thingRepository)
    {
        $this->thingRepository = $thingRepository;
    }

    public function list(): Response
    {
        $things = $this->thingRepository->findAll();

        return $this->render('things/list.html.twig', ['things' => $things]);
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
        ]);
    }

    public function delete(Request $request, int $thingId): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirect('/things');
        }

        $thing = $this->thingRepository->find($thingId);

        if (null !== $thing) {
            $this->thingRepository->delete($thing);
        }

        return $this->redirectToRoute('things');
    }
}
