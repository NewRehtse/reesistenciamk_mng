<?php

namespace App\Controller;

use App\Orchestrator\OrchestratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class TaskController extends AbstractController
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
            $content = $this->orchestrator->content($request, 'task-list');
        } catch (AccessDeniedException $accessDeniedException) {
            $this->addFlash('warning', 'No tienes acceso a listar los lotes');

            return $this->redirectToRoute('home');
        }

        return $this->render('tasks/list.html.twig', $content);
    }

    public function detail(Request $request): Response
    {
        try {
            $content = $this->orchestrator->content($request, 'task-detail');
        } catch (AccessDeniedException $accessDeniedException) {
            $this->addFlash('warning', 'No tienes acceso a ver detalle de este lote');

            return $this->redirectToRoute('tasks');
        }

        if ([] === $content) {
            return $this->redirectToRoute('tasks');
        }

        return $this->render('tasks/detail.html.twig', $content);
    }

    public function create(Request $request): Response
    {
        try {
            $content = $this->orchestrator->content($request, 'task-create');
        } catch (\InvalidArgumentException $invalidArgumentException) {
            $this->addFlash('error', $invalidArgumentException->getMessage());

            return $this->redirectToRoute('tasks', []);
        } catch (AccessDeniedException $accessDeniedException) {
            $this->addFlash('warning', 'No tienes acceso crear lotes');

            return $this->redirectToRoute('tasks');
        }

        if (isset($content['taskId'])) {
            $this->addFlash(
                    'info',
                    'Lote creado'
            );

            return $this->redirectToRoute('task.detail', ['taskId' => $content['taskId']]);
        }

        return $this->render('tasks/create.html.twig', [
                'form' => $content['form'],
        ]);
    }

    public function edit(Request $request): Response
    {
        try {
            $content = $this->orchestrator->content($request, 'task-edit');
        } catch (AccessDeniedException $accessDeniedException) {
            $this->addFlash('warning', 'No tienes acceso editar este lote');

            return $this->redirectToRoute('tasks');
        } catch (\InvalidArgumentException $invalidArgumentException) {
            $this->addFlash('error', $invalidArgumentException->getMessage());

            return $this->redirectToRoute('tasks', []);
        }

        if (isset($content['taskId'])) {
            $this->addFlash(
                    'info',
                    'Lote actualizado'
            );

            return $this->redirectToRoute('task.detail', ['taskId' => $content['taskId']]);
        }

        return $this->render('tasks/create.html.twig', [
                'form' => $content['form'],
        ]);
    }

    public function updateStatus(Request $request, int $taskId): Response
    {
        try {
            $this->orchestrator->content($request, 'task-update-status');
        } catch (AccessDeniedException $accessDeniedException) {
            $this->addFlash('warning', 'No tienes acceso editar este lote');

            return $this->redirectToRoute('tasks');
        } catch (NotFoundHttpException $notFoundHttpException) {
            $this->addFlash('error', $notFoundHttpException->getMessage());

            return $this->redirectToRoute('tasks', []);
        }

        $this->addFlash(
                'info',
                'Estado del lote actualizado'
        );

        return $this->redirectToRoute('tasks', []);
    }
}
