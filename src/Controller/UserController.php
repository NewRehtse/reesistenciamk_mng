<?php

namespace App\Controller;

use App\Form\Type\EditPassword;
use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\Entity\User;
use App\Persistence\Doctrine\Repository\UserRepository;
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
    private $userRepository;

    /** @var OrchestratorInterface */
    private $orchestrator;

    public function __construct(UserRepository $userRepository, OrchestratorInterface $orchestrator)
    {
        $this->userRepository = $userRepository;
        $this->orchestrator = $orchestrator;
    }

    public function profile(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('users/profile.html.twig', ['user' => $user]);
    }

    public function profileEdit(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $content = $this->orchestrator->content($request, 'user-profile-edit');

        if (isset($content['form'])) {
            return $this->render('users/profile-edit.html.twig', $content);
        }

        $this->addFlash('info', 'Cambios guardados');

        return $this->redirectToRoute('users.profile');
    }

    public function editPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        try {
            $content = $this->orchestrator->content($request, 'user-password-edit');
        } catch (\InvalidArgumentException $invalidArgumentException) {
            $this->addFlash('error', $invalidArgumentException->getMessage());
            //Realmente redirige al login
            return $this->redirectToRoute('users.profile');
        }

        if (isset($content['form'])) {
            return $this->render('users/profile-edit.html.twig', $content);
        }

        $this->addFlash(
                'success',
                'Contraseña cambiada.'
        );

        return $this->redirectToRoute('users.profile');
    }

    public function adminUsers(Request $request): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('tasks');
        }

        $content = $this->orchestrator->content($request, 'user-list');

        return $this->render('users/admin-users.html.twig', $content);
    }

    public function adminUsersEdit(Request $request, int $userId): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
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
        if (!$this->isGranted('ROLE_ADMIN')) {
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
        if (!$this->isGranted('ROLE_ADMIN')) {
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
