<?php

namespace App\Controller;

use App\Orchestrator\OrchestratorInterface;
use App\Security\AdminUsersVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfigurationController extends AbstractController
{
    /** @var OrchestratorInterface */
    private $orchestrator;

    public function __construct(OrchestratorInterface $orchestrator)
    {
        $this->orchestrator = $orchestrator;
    }

    public function configuration(Request $request): Response
    {
        if (!$this->isGranted(AdminUsersVoter::LIST)) {
            return $this->redirectToRoute('tasks');
        }

        $content = $this->orchestrator->content($request, 'admin-configuration');

        return $this->render('admin/configuration.html.twig', $content);
    }
}
