<?php

namespace App\Orchestrator\Thing;

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
        $thingId = $request->attributes->get('thingId');

        $thing = $this->generalRepository->findThing($thingId);

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
