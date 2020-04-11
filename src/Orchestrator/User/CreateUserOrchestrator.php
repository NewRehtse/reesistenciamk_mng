<?php

namespace App\Orchestrator\User;

use App\Form\Type\CreateUserType;
use App\Orchestrator\OrchestratorInterface;
use App\Persistence\Doctrine\Entity\Delivery;
use App\Persistence\Doctrine\Entity\Maker;
use App\Persistence\Doctrine\Entity\User;
use App\Persistence\Doctrine\GeneralDoctrineRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class CreateUserOrchestrator implements OrchestratorInterface
{
    /** @var GeneralDoctrineRepository */
    private $generalRepository;

    /** @var Security */
    private $security;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    public function __construct(
            GeneralDoctrineRepository $generalDoctrineRepository,
            FormFactoryInterface $formFactory,
            Security $security,
            UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->generalRepository = $generalDoctrineRepository;
        $this->security = $security;
        $this->formFactory = $formFactory;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @return array<string, mixed>
     */
    public function content(Request $request, string $type): array
    {
        $userId = $request->attributes->get('userId', null);

        $user = new User();
        if (null !== $userId) {
            $user = $this->generalRepository->findUser($userId);
        }

        $form = $this->formFactory->create(CreateUserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            if (\in_array('ROLE_MAKER', $user->getRoles(), true)) {
                $maker = new Maker();
                $maker->setUser($user);
                $user->setMaker($maker);
            }
            if (\in_array('ROLE_DELIVERY', $user->getRoles(), true)) {
                $delivery = new Delivery();
                $delivery->setUser($user);
                $user->setDelivery($delivery);
            }
            $password = $this->passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $this->generalRepository->saveUser($user);

            return [];
        }

        return ['form' => $form->createView()];
    }

    public function canHandleContentOfType(string $type): bool
    {
        $validTypes = ['user-create', 'user-edit'];

        return \in_array($type, $validTypes, true);
    }
}
