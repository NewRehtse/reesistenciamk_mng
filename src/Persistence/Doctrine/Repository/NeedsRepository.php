<?php

namespace App\Persistence\Doctrine\Repository;

use App\Persistence\Doctrine\Entity\Needs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Needs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Needs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Needs[]    findAll()
 * @method Needs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NeedsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Needs::class);
    }

    public function delete(Needs $need): void
    {
        $this->getEntityManager()->remove($need);
        $this->getEntityManager()->flush();
    }

    public function save(Needs $need): void
    {
        $this->getEntityManager()->persist($need);
        $this->getEntityManager()->flush();
    }
}
