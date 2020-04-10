<?php

namespace App\Controller;

use App\Orchestrator\OrchestratorInterface;
use App\Security\PlaceVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class PlaceController extends AbstractController
{
    /** @var OrchestratorInterface */
    private $orchestrator;

    public function __construct(OrchestratorInterface $orchestrator)
    {
        $this->orchestrator = $orchestrator;
    }

    public function list(Request $request): Response
    {
        $content = $this->orchestrator->content($request, 'place-list');

        return $this->render('places/list.html.twig', $content);
    }

    public function create(Request $request): Response
    {
        if (!$this->isGranted(PlaceVoter::CREATE)) {
            return $this->redirectToRoute('places');
        }

        $content = $this->orchestrator->content($request, 'place-create');

        if (isset($content['error'])) {
            $this->addFlash('error', $content['error']);

            return $this->render('places/create.html.twig', $content);
        }

        if (isset($content['place'])) {
            $this->addFlash('info', 'Demandante creado.');

            return $this->redirectToRoute('places');
        }

        return $this->render('places/create.html.twig', $content);
    }

    public function edit(Request $request): Response
    {
        if (!$this->isGranted(PlaceVoter::CREATE)) {
            return $this->redirectToRoute('places');
        }

        $content = $this->orchestrator->content($request, 'place-edit');

        if (isset($content['place'])) {
            $this->addFlash('info', 'Demandante editado');

            return $this->redirectToRoute('places');
        }

        return $this->render('places/update.html.twig', $content);
    }

    public function delete(Request $request, int $placeId): Response
    {
        if (!$this->isGranted(PlaceVoter::DELETE)) {
            return $this->redirectToRoute('places');
        }

        try {
            $this->orchestrator->content($request, 'place-delete');
        } catch (NotFoundHttpException $e) {
            $this->addFlash('error', $e->getMessage());
        } catch (\InvalidArgumentException $invalidArgumentException) {
            $this->addFlash('error', $invalidArgumentException->getMessage());
        }

        return $this->redirectToRoute('places');
    }

    public function addNeeds(Request $request): Response
    {
//        if (!$this->isGranted('ROLE_ADMIN')) {
//            return $this->redirect('/places');
//        }

        try {
            $content = $this->orchestrator->content($request, 'place-add-needs');

            if (isset($content['placeId'])) {
                $this->addFlash('info', 'Solicitud añadida.');

                return $this->redirectToRoute('places.needs.list', ['placeId' => $content['placeId']]);
            }
        } catch (NotFoundHttpException $e) {
            $this->addFlash('error', $e->getMessage());

            return $this->redirectToRoute('places');
        }

        return $this->render('places/addNeeds.html.twig', [
            'form' => $content['form'],
        ]);
    }

    public function needs(Request $request, int $placeId): Response
    {
        $content = $this->orchestrator->content($request, 'place-list-needs');

        return $this->render('places/needs_list.html.twig', $content);
    }

    public function coverNeed(Request $request): Response
    {
        if (!$this->isGranted(PlaceVoter::COVER_NEED)) {
            return $this->redirect('/places');
        }

        try {
            $content = $this->orchestrator->content($request, 'place-cover-needs');
            if (isset($content['placeId'])) {
                $this->addFlash('info', 'Solicitud cubierta');

                return $this->redirectToRoute('places.needs.list', ['placeId' => $content['placeId']]);
            }
        } catch (NotFoundHttpException $e) {
            $this->addFlash('error', $e->getMessage());

            return $this->redirectToRoute('places');
        }

        return $this->render('places/coverNeed.html.twig', [
            'form' => $content['form'],
        ]);
    }
}
