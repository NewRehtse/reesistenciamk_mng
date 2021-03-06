<?php

namespace App\Orchestrator\Thing;

use App\Form\Type\EditAdminThingType;
use App\Form\Type\EditThingType;
use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\Entity\Thing;
use App\Persistence\Doctrine\Entity\User;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use App\Security\ThingVoter;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class CreateOrchestrator implements OrchestratorInterface
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
        if ('thing-create' === $type && !$this->security->isGranted(ThingVoter::CREATE)) {
            throw new AccessDeniedException();
        }

        $thing = new Thing();
        /** @var User $user */
        $user = $this->security->getUser();
        $thing->setOwner($user);

        $form = $this->formFactory->create(EditThingType::class, $thing);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Thing $thing */
            $thing = $form->getData();

            if ($this->security->isGranted(ThingVoter::CREATE_VALID)) {
                $thing->setValid(true);
            }

            $this->generalRepository->saveThing($thing);

            return ['thing' => $thing];
        }

        return [
            'form' => $form->createView(),
            'thing' => $thing,
        ];
    }

    public function canHandleContentOfType(string $type): bool
    {
        $validTypes = ['thing-create'];

        return \in_array($type, $validTypes, true);
    }
}
