<?php

namespace App\Orchestrator\Place;

use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use App\Security\PlaceVoter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
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
        $placeId = $request->attributes->get('placeId');
        $place = $this->generalRepository->findPlace($placeId);

        if (!$this->security->isGranted(PlaceVoter::DELETE, $place)) {
            throw new AccessDeniedException();
        }

        if (null === $place) {
            throw new NotFoundHttpException('Demandante no encontrado');
        }

        if ($place->needs()->count() > 0) {
            throw new \InvalidArgumentException('No se puede borrar si tiene solicitudes abiertas');
        }

        $this->generalRepository->deletePlace($place);

        return [];
    }

    public function canHandleContentOfType(string $type): bool
    {
        $validTypes = ['place-delete'];

        return \in_array($type, $validTypes, true);
    }
}
