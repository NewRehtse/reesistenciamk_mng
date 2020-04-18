<?php

namespace App\Controller;

use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\Entity\Needs;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DashboardController extends AbstractController
{
    /** @var OrchestratorInterface */
    private $orchestrator;

    public function __construct(OrchestratorInterface $orchestrator)
    {
        $this->orchestrator = $orchestrator;
    }

    public function index(Request $request): Response
    {
        if ('POST' === $request->getMethod()) {
            $needs = $request->get('needs', '');
            $collect = $request->get('collect', '');
            if ('' !== $needs) {
                $this->needs($request);
            }
            if ('' !== $collect) {
                $this->collect($request);
            }
        }

        $content = $this->orchestrator->content($request, 'dashboard-index');

        return $this->render('dashboard/dashboard.html.twig', $content);
    }

    private function needs(Request $request): void
    {
//        $this->denyAccessUnlessGranted('add_need');
        try {
            $this->orchestrator->content($request, 'dashboard-add-needs');

            $this->addFlash(
                    'info',
                    'Material solicitado!'
            );
        } catch (NotFoundHttpException $e) {
            $this->addFlash('error', $e->getMessage());
        }
    }

    private function collect(Request $request): void
    {
//        $user = $this->getUser();
//        $this->denyAccessUnlessGranted('add_collect', $user);
        try {
            $this->orchestrator->content($request, 'dashboard-request-collect');

            $this->addFlash(
                    'info',
                    'Recogida solicitada!'
            );
        } catch (\InvalidArgumentException $e) {
            $this->addFlash('error', $e->getMessage());
        }
    }
}
