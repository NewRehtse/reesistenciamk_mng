<?php

namespace App\Orchestrator\Place;

use App\Form\Type\CoverNeedType;
use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class CoverNeedsOrchestrator implements OrchestratorInterface
{
    private $generalRepository;
    private $formFactory;

    public function __construct(GeneralDoctrineRepository $generalDoctrineRepository, FormFactoryInterface $formFactory)
    {
        $this->generalRepository = $generalDoctrineRepository;
        $this->formFactory = $formFactory;
    }

    public function content(Request $request, string $type): array
    {
        $needId = $request->attributes->get('needId');
        $placeId = $request->attributes->get('placeId');

        $need = $this->generalRepository->findNeeds($needId);

        if (null === $need) {
            throw new NotFoundHttpException('No se ha encontrado la solicitud a cubrir.');
        }

        $form = $this->formFactory->create(CoverNeedType::class, $need);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->generalRepository->saveNeeds($need);

            return ['placeId' => $placeId];
        }

        return ['form' => $form->createView()];
    }

    public function canHandleContentOfType(string $type): bool
    {
        $validTypes = ['place-cover-needs'];

        return \in_array($type, $validTypes, true);
    }
}
