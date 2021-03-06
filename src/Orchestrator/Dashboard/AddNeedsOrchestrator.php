<?php

namespace App\Orchestrator\Dashboard;

use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\Entity\Needs;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AddNeedsOrchestrator implements OrchestratorInterface
{
    /** @var GeneralDoctrineRepository */
    private $generalRepository;

    public function __construct(GeneralDoctrineRepository $generalDoctrineRepository)
    {
        $this->generalRepository = $generalDoctrineRepository;
    }

    /**
     * @return array<string, mixed>
     */
    public function content(Request $request, string $type): array
    {
        //SOlicitar material
        $placeId = (int) $request->get('place', '');
        $thingId = (int) $request->get('thing', '');
        $amount = (int) $request->get('amount', 0);

        if (0 === $amount) {
            throw new NotFoundHttpException('La cantidad no puede ser 0');
        }

        $place = $this->generalRepository->findPlace($placeId);
        if (null === $place) {
            throw new NotFoundHttpException('Demandante no encontrado');
        }
        $thing = $this->generalRepository->findThing($thingId);
        if (null === $thing) {
            throw new NotFoundHttpException('Imprimible no encontrado');
        }

        $need = new Needs();
        $need->setThing($thing);
        $need->setPlace($place);
        $need->setAmount($amount);

        $this->generalRepository->saveNeeds($need);

        return [];
    }

    /**
     * @inheritDoc
     */
    public function canHandleContentOfType(string $type): bool
    {
        $validTypes = ['dashboard-add-needs'];

        return \in_array($type, $validTypes, true);
    }
}
