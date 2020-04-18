<?php

namespace App\Controller;

use App\Orchestrator\OrchestratorInterface;
use App\Security\PlaceVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
        try {
            $content = $this->orchestrator->content($request, 'place-list');
        } catch (AccessDeniedException $accessDeniedException) {
            $this->addFlash('warning', 'No tienes acceso a listar demandantes');

            return $this->redirectToRoute('home');
        }

        return $this->render('places/list.html.twig', $content);
    }

    public function create(Request $request): Response
    {
        try {
            $content = $this->orchestrator->content($request, 'place-create');
        } catch (AccessDeniedException $accessDeniedException) {
            $this->addFlash('warning', 'No tienes acceso a crear demandantes');

            return $this->redirectToRoute('places');
        }

        if (isset($content['error'])) {
            $this->addFlash('error', $content['error']);

            return $this->render('places/create.html.twig', $content);
        }

        if (!isset($content['form'])) {
            $this->addFlash('info', 'Demandante creado.');

            return $this->redirectToRoute('places');
        }

        return $this->render('places/create.html.twig', $content);
    }

    public function edit(Request $request): Response
    {
        if (!$this->isGranted(PlaceVoter::EDIT)) {
            $this->addFlash('warning', 'No tienes acceso a editar este demandante');

            return $this->redirectToRoute('places');
        }

        $content = $this->orchestrator->content($request, 'place-edit');

        if (!isset($content['form'])) {
            $this->addFlash('info', 'Demandante editado');

            return $this->redirectToRoute('places');
        }

        return $this->render('places/update.html.twig', $content);
    }

    public function delete(Request $request, int $placeId): Response
    {
        try {
            $this->orchestrator->content($request, 'place-delete');
            $this->addFlash('info', 'Demandante borrado');
        } catch (AccessDeniedException $accessDeniedException) {
            $this->addFlash('warning', 'No tienes acceso a borrar este demandante');
        } catch (NotFoundHttpException $e) {
            $this->addFlash('error', $e->getMessage());
        } catch (\InvalidArgumentException $invalidArgumentException) {
            $this->addFlash('error', $invalidArgumentException->getMessage());
        }

        return $this->redirectToRoute('places');
    }

    public function addNeeds(Request $request): Response
    {
        try {
            $content = $this->orchestrator->content($request, 'place-add-needs');
        } catch (AccessDeniedException $accessDeniedException) {
            $this->addFlash('warning', 'No tienes acceso a añadir necesidades a este demandante');

            return $this->redirectToRoute('places');
        } catch (NotFoundHttpException $e) {
            $this->addFlash('error', $e->getMessage());

            return $this->redirectToRoute('places');
        }

        if (isset($content['placeId'])) {
            $this->addFlash('info', 'Solicitud añadida.');

            return $this->redirectToRoute('places.needs.list', ['placeId' => $content['placeId']]);
        }

        return $this->render('places/addNeeds.html.twig', [
            'form' => $content['form'],
        ]);
    }

    public function needs(Request $request, int $placeId): Response
    {
        try {
            $content = $this->orchestrator->content($request, 'place-list-needs');
        } catch (AccessDeniedException $accessDeniedException) {
            $this->addFlash('warning', 'No tienes acceso a listar las necesidades a este demandante');

            return $this->redirectToRoute('places');
        }

        return $this->render('places/needs_list.html.twig', $content);
    }

    public function coverNeed(Request $request): Response
    {
        try {
            $content = $this->orchestrator->content($request, 'place-cover-needs');
            if (isset($content['placeId'])) {
                $this->addFlash('info', 'Solicitud cubierta');

                return $this->redirectToRoute('places.needs.list', ['placeId' => $content['placeId']]);
            }
        } catch (AccessDeniedException $accessDeniedException) {
            $this->addFlash('warning', 'No tienes acceso a cubrir las necesidades de este demandante');

            return $this->redirectToRoute('places');
        } catch (NotFoundHttpException $e) {
            $this->addFlash('error', $e->getMessage());

            return $this->redirectToRoute('places');
        }

        return $this->render('places/coverNeed.html.twig', [
            'form' => $content['form'],
        ]);
    }
}
