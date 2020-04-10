<?php

namespace App\Orchestrator\Place;

use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class DeleteOrchestrator implements OrchestratorInterface
{
    private $generalRepository;

    public function __construct(GeneralDoctrineRepository $generalDoctrineRepository)
    {
        $this->generalRepository = $generalDoctrineRepository;
    }

    public function content(Request $request, string $type): array
    {
        $placeId = $request->attributes->get('placeId');
        $place = $this->generalRepository->findPlace($placeId);

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
