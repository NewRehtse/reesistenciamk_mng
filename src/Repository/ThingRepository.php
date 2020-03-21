<?php

namespace App\Repository;

use App\Entity\Thing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Thing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Thing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Thing[]    findAll()
 * @method Thing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Thing::class);
    }

    public function delete(Thing $thing): void
    {
        $this->getEntityManager()->remove($thing);
        $this->getEntityManager()->flush();
    }

    public function save(Thing $thing): void
    {
        $this->getEntityManager()->persist($thing);
        $this->getEntityManager()->flush();
    }
}
