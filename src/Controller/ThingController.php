<?php

namespace App\Controller;

use App\Orchestrator\OrchestratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ThingController extends AbstractController
{
    /** @var OrchestratorInterface */
    private $orchestrator;

    public function __construct(OrchestratorInterface $orchestrator)
    {
        $this->orchestrator = $orchestrator;
    }

    public function list(Request $request): Response
    {
        $route = $request->get('_route');
        $isAdminRoute = 1 === \preg_match('/^admin\./', $route);

        try {
            $content = $this->orchestrator->content($request, 'thing-list');
        } catch (AccessDeniedException $accessDeniedException) {
            $this->addFlash('warning', 'No tienes acceso a listar imprimibles');

            return $this->redirectToRoute('home');
        }

        if ($isAdminRoute) {
            return $this->render('things/admin.list.html.twig', $content);
        }

        return $this->render('things/list.html.twig', $content);
    }

    public function create(Request $request): Response
    {
        try {
            $content = $this->orchestrator->content($request, 'thing-create');
        } catch (AccessDeniedException $accessDeniedException) {
            $this->addFlash('warning', 'No tienes acceso a crear imprimibles');

            return $this->redirectToRoute('things');
        }

        if (!isset($content['form'])) {
            $this->addFlash(
                    'info',
                    'Imprimible creado creado'
            );

            return $this->redirectToRoute('things');
        }

        return $this->render('things/create.html.twig', $content);
    }

    public function update(Request $request, int $thingId): Response
    {
        try {
            $content = $this->orchestrator->content($request, 'thing-update');
        } catch (AccessDeniedException $accessDeniedException) {
            $this->addFlash('warning', 'No tienes acceso a editar este imprimible');

            return $this->redirectToRoute('things');
        }

        if (!isset($content['form'])) {
            $this->addFlash(
                    'info',
                    'Imprimible creado creado'
            );

            return $this->redirectToRoute('things');
        }

        return $this->render('things/update.html.twig', $content);
    }

    public function delete(Request $request, int $thingId): Response
    {
        try {
            $this->orchestrator->content($request, 'thing-delete');
        } catch (AccessDeniedException $accessDeniedException) {
            $this->addFlash('warning', 'No tienes acceso a borrar imprimibles');

            return $this->redirectToRoute('things');
        } catch (NotFoundHttpException $notFoundHttpException) {
            $this->addFlash('error', $notFoundHttpException->getMessage());

            return $this->redirectToRoute('things');
        } catch (\InvalidArgumentException $invalidArgumentException) {
            $this->addFlash('error', $invalidArgumentException->getMessage());

            return $this->redirectToRoute('things');
        }

        $this->addFlash(
                'info',
                'Imprimible borrado'
        );

        return $this->redirectToRoute('things');
    }

    public function detail(Request $request): Response
    {
        $route = $request->get('_route');
//        $isAdminRoute = 1 === \preg_match('/^admin\./', $route);

        try {
            $content = $this->orchestrator->content($request, 'thing-detail');
        } catch (AccessDeniedException $accessDeniedException) {
            $this->addFlash('warning', 'No tienes acceso al detalle imprimibles');

            return $this->redirectToRoute('things');
        }

//        if ($isAdminRoute) {
//            return $this->render('things/admin.detail.html.twig', $content);
//        }
        return $this->render('things/detail.html.twig', $content);
    }
}
