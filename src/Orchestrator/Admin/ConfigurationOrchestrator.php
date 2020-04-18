<?php

namespace App\Orchestrator\Admin;

use App\Form\Type\ConfigurationType;
use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\Entity\Configuration;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use App\Security\PlaceVoter;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class ConfigurationOrchestrator implements OrchestratorInterface
{
    /** @var GeneralDoctrineRepository */
    private $generalRepository;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var Security */
    private $security;

    public function __construct(
            GeneralDoctrineRepository $generalDoctrineRepository,
            FormFactoryInterface $formFactory,
            Security $security
    ) {
        $this->generalRepository = $generalDoctrineRepository;
        $this->formFactory = $formFactory;
        $this->security = $security;
    }

    /**
     * @return array<string, mixed>
     */
    public function content(Request $request, string $type): array
    {
        if (!$this->security->isGranted(PlaceVoter::CREATE)) {
            throw new AccessDeniedException();
        }

        $configuration = $this->generalRepository->getConfiguration();

        $form = $this->formFactory->create(ConfigurationType::class, $configuration);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Configuration $configuration */
            $configuration = $form->getData();

            $this->generalRepository->saveConfiguration($configuration);
        }

        $result['form'] = $form->createView();

        return $result;
    }

    public function canHandleContentOfType(string $type): bool
    {
        $validTypes = ['admin-configuration'];

        return \in_array($type, $validTypes, true);
    }
}
