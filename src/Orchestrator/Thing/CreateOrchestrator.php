<?php

namespace App\Orchestrator\Thing;

use App\Form\Type\ThingType;
use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\Entity\Thing;
use App\Persistence\Doctrine\Entity\User;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use App\Security\ThingVoter;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
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

        if ('thing-update' === $type) {
            $thingId = $request->attributes->get('thingId');
            $thing = $this->generalRepository->findThing($thingId);

            if (!$this->security->isGranted(ThingVoter::EDIT, $thing)) {
                throw new AccessDeniedException();
            }
        }

        $form = $this->formFactory->create(ThingType::class, $thing);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $thing = $form->getData();

            $this->generalRepository->saveThing($thing);

            return ['thingId' => $thing->id()];
        }

        return [
                'form' => $form->createView(),
            'hasTasks' => $thing->tasks()->count() > 0,
        ];
    }

    public function canHandleContentOfType(string $type): bool
    {
        $validTypes = ['thing-create', 'thing-update'];

        return \in_array($type, $validTypes, true);
    }
}
