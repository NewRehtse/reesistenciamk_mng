<?php

namespace App\Controller;

use App\Orchestrator\OrchestratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $content = $this->orchestrator->content($request, 'task-list');

        return $this->render('tasks/list.html.twig', $content);
    }

    public function detail(Request $request, int $taskId): Response
    {
        $content = $this->orchestrator->content($request, 'task-detail');

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

    public function updateStatus(Request $request, int $taskId): Response
    {
        try {
            $this->orchestrator->content($request, 'task-update-status');
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
