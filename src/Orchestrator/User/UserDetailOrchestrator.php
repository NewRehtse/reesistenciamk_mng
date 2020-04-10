<?php

namespace App\Orchestrator\User;

use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class UserDetailOrchestrator implements OrchestratorInterface
{
    private $generalRepository;

    public function __construct(GeneralDoctrineRepository $generalDoctrineRepository)
    {
        $this->generalRepository = $generalDoctrineRepository;
    }

    public function content(Request $request, string $type): array
    {
        $userId = $request->attributes->get('userId');

        $user = $this->generalRepository->findUser($userId);

        if (null === $user) {
            throw new NotFoundHttpException('No se ha encontrado al usuario');
        }

        return ['user' => $user];
    }

    public function canHandleContentOfType(string $type): bool
    {
        $validTypes = ['user-detail'];

        return \in_array($type, $validTypes, true);
    }
}
