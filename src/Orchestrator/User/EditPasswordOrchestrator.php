<?php

namespace App\Orchestrator\User;

use App\Form\Type\EditPassword;
use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\Entity\User;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\NativePasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class EditPasswordOrchestrator implements OrchestratorInterface
{
    private $generalRepository;
    private $security;
    private $formFactory;
    private $passwordEncoder;

    public function __construct(
            GeneralDoctrineRepository $generalDoctrineRepository,
            FormFactoryInterface $formFactory,
            Security $security,
            UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->generalRepository = $generalDoctrineRepository;
        $this->security = $security;
        $this->formFactory = $formFactory;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function content(Request $request, string $type): array
    {
        /** @var User $user */
        $user = $this->security->getUser();
        $oldPassword = $user->getPassword();

        $form = $this->formFactory->create(EditPassword::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $request->request->get('edit_password');
            $currentPassword = $formData['current'] ?? '';
            $encoder = new NativePasswordEncoder();
            $valid = $encoder->isPasswordValid($oldPassword, $currentPassword, '');

            if (!$valid) {
                $this->generalRepository->refreshUser($user);
                throw new \InvalidArgumentException('La contraseña actual no coincide, vuelve a hacer login e intenta cambiarla de nuevo.');
            }

            /** @var User $user */
            $user = $form->getData();
            $password = $this->passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $this->generalRepository->saveUser($user);

            $this->generalRepository->refreshUser($user);

            return [];
        }

        return ['form' => $form->createView()];
    }

    public function canHandleContentOfType(string $type): bool
    {
        $validTypes = ['user-password-edit'];

        return \in_array($type, $validTypes, true);
    }
}
