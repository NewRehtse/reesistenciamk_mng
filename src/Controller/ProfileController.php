<?php

namespace App\Controller;

use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfileController extends AbstractController
{
    /** @var OrchestratorInterface */
    private $orchestrator;

    public function __construct(OrchestratorInterface $orchestrator)
    {
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

    public function editPassword(Request $request): Response
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
}
