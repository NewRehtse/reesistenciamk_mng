<?php

namespace App\Orchestrator\User;

use App\Form\Type\EditProfile;
use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\Entity\User;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class ProfileEditOrchestrator implements OrchestratorInterface
{
    /** @var GeneralDoctrineRepository */
    private $generalRepository;

    /** @var Security */
    private $security;

    /** @var FormFactoryInterface */
    private $formFactory;

    public function __construct(
            GeneralDoctrineRepository $generalDoctrineRepository,
            FormFactoryInterface $formFactory,
            Security $security
    ) {
        $this->generalRepository = $generalDoctrineRepository;
        $this->security = $security;
        $this->formFactory = $formFactory;
    }

    /**
     * @return array<string, mixed>
     */
    public function content(Request $request, string $type): array
    {
        $user = $this->security->getUser();

        $form = $this->formFactory->create(EditProfile::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();

            $this->generalRepository->saveUser($user);

            return [];
        }

        return ['form' => $form->createView()];
    }

    public function canHandleContentOfType(string $type): bool
    {
        $validTypes = ['user-profile-edit'];

        return \in_array($type, $validTypes, true);
    }
}
