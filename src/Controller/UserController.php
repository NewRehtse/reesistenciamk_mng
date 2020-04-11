<?php

namespace App\Controller;

use App\Orchestrator\OrchestratorInterface;
use App\Security\AdminUsersVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class UserController extends AbstractController
{
    /** @var OrchestratorInterface */
    private $orchestrator;

    public function __construct(OrchestratorInterface $orchestrator)
    {
        $this->orchestrator = $orchestrator;
    }

    public function adminUsers(Request $request): Response
    {
        if (!$this->isGranted(AdminUsersVoter::LIST)) {
            return $this->redirectToRoute('tasks');
        }

        $content = $this->orchestrator->content($request, 'user-list');

        return $this->render('users/admin-users.html.twig', $content);
    }

    public function adminUsersEdit(Request $request, int $userId): Response
    {
        if (!$this->isGranted(AdminUsersVoter::EDIT)) {
            return $this->redirectToRoute('tasks');
        }

        $content = $this->orchestrator->content($request, 'user-edit');

        if (isset($content['form'])) {
            return $this->render('users/admin-users-create.html.twig', $content);
        }

        $this->addFlash('info', 'Usuario editado.');

        return $this->redirectToRoute('users.admin.detail', ['userId' => $userId]);
    }

    public function adminUsersCreate(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if (!$this->isGranted(AdminUsersVoter::CREATE)) {
            return $this->redirectToRoute('tasks');
        }

        $content = $this->orchestrator->content($request, 'user-create');

        if (isset($content['form'])) {
            return $this->render('users/admin-users-create.html.twig', $content);
        }

        $this->addFlash('info', 'Usuario creado.');

        return $this->redirectToRoute('users.admin');
    }

    public function adminUsersDetail(Request $request, int $userId): Response
    {
        if (!$this->isGranted(AdminUsersVoter::VIEW)) {
            return $this->redirectToRoute('tasks');
        }

        try {
            $content = $this->orchestrator->content($request, 'user-detail');
        } catch (NotFoundHttpException $notFoundHttpException) {
            $this->addFlash('error', $notFoundHttpException->getMessage());

            return $this->redirectToRoute('users.admin');
        }

        return $this->render('users/admin-users-detail.html.twig', $content);
    }
}
