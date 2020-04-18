<?php

namespace App\Persistence\Doctrine\Repository;

use App\Persistence\Doctrine\Entity\RequestCollect;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RequestCollectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RequestCollect::class);
    }

    public function save(RequestCollect $collect): void
    {
        $this->getEntityManager()->persist($collect);
        $this->getEntityManager()->flush();
    }
}
