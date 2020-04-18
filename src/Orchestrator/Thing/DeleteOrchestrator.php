<?php

namespace App\Orchestrator\Thing;

use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use App\Security\ThingVoter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class DeleteOrchestrator implements OrchestratorInterface
{
    /** @var GeneralDoctrineRepository */
    private $generalRepository;

    /** @var Security */
    private $security;

    public function __construct(
            GeneralDoctrineRepository $generalDoctrineRepository,
            Security $security
    ) {
        $this->generalRepository = $generalDoctrineRepository;
        $this->security = $security;
    }

    /**
     * @return array<string, mixed>
     */
    public function content(Request $request, string $type): array
    {
        $thingId = $request->attributes->get('thingId');

        $thing = $this->generalRepository->findThing($thingId);

        if (!$this->security->isGranted(ThingVoter::DELETE, $thing)) {
            throw new AccessDeniedException();
        }

        if (null === $thing) {
            throw new NotFoundHttpException('Imprimible no encontrado');
        }

        if ($thing->tasks()->count() > 0) {
            throw new \InvalidArgumentException('Imprimible no se puede borrar porque tiene lotes asociados');
        }

        $this->generalRepository->removeThing($thing);

        return [];
    }

    public function canHandleContentOfType(string $type): bool
    {
        $validTypes = ['thing-delete'];

        return \in_array($type, $validTypes, true);
    }
}
