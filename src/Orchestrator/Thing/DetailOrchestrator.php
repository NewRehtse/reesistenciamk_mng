<?php

namespace App\Orchestrator\Thing;

use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use App\Security\ThingVoter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class DetailOrchestrator implements OrchestratorInterface
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
        $thingId = $request->get('thingId', '');

        if (empty($thingId)) {
            throw new NotFoundHttpException('Imprimible no encontrado');
        }

        $thing = $this->generalRepository->findThing($thingId);

        if (!$this->security->isGranted(ThingVoter::VIEW, $thing)) {
            throw new AccessDeniedException();
        }

        return ['thing' => $thing];
    }

    public function canHandleContentOfType(string $type): bool
    {
        $validTypes = ['thing-detail'];

        return \in_array($type, $validTypes, true);
    }
}
