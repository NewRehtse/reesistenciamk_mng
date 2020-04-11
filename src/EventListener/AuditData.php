<?php

namespace App\EventListener;

use App\Persistence\Doctrine\Entity\User;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AuditData
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    //Datos de audicion cuando se crea la entidad
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        $token = $this->tokenStorage->getToken();

        // Si nos llega ya con un usario creador, lo respetamos, para cuando importamos/exportamos
        if (null !== $token && !$entity->getCrtUser()) {
            $email = '';
            $user = $token->getUser();
            if ($user instanceof User) {
                $email = $user->getEmail();
            } elseif ($entity instanceof User) {
                $email = $entity->getEmail(); //When registration
            }

            $entity->setCrtUser($email);
        }

        // Idem para la fecha
        if (!$entity->getCrtDate()) {
            $entity->setCrtDate(new \DateTime());
        }
    }

    //Datos de audición cuando se modifica la entidad
    public function preUpdate(LifecycleEventArgs $args)
    { //Solo cuando se crea
        $entity = $args->getObject();

        $token = $this->tokenStorage->getToken();
        if (null === $token) {
            return;
        }

        $user = $token->getUser()->getEmail();

        $entity->setUpdUser($user);
        $entity->setUpdDate(new \DateTime());
    }
}
