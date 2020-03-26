<?php

namespace App\EventListener;

// use App\Entity\History;
// use App\Entity\Session;
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

        //Control para que si no es instancia de las que hemos puesto aqui, se salga
        // (entidades que no tienen datos de audición)
        // if ($entity instanceof Session || $entity instanceof History) {
        //     return;
        // }

        // Si nos llega ya con un usario creador, lo respetamos, para cuando importamos/exportamos
        if (!$entity->getCrtUser()) {
            $user = $this->tokenStorage->getToken()->getUser()->getUsername();

            $entity->setCrtUser($user);
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

        //Control para que si no es instancia de las que hemos puesto aqui, se salga
        // (entidades que no tienen datos de audición)
        // if ($entity instanceof Session || $entity instanceof History) {
        //     return;
        // }

        $user = $this->tokenStorage->getToken()->getUser()->getUsername();

        $entity->setUpdUser($user);
        $entity->setUpdDate(new \DateTime());
    }
}
