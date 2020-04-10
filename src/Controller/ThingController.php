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
        $content = $this->orchestrator->content($request, 'thing-list');

        return $this->render('things/list.html.twig', $content);
    }

    public function create(Request $request): Response
    {
//        if (!$this->isGranted('ROLE_ADMIN')) {
//            return $this->redirect('/things');
//        }
        $content = $this->orchestrator->content($request, 'thing-create');

        if (isset($content['thingId'])) {
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
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirect('/things');
        }

        $content = $this->orchestrator->content($request, 'thing-update');

        if (isset($content['thingId'])) {
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
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirect('/things');
        }

        try {
            $this->orchestrator->content($request, 'thing-delete');
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
}
