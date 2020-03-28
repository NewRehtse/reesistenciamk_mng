<?php

namespace App\Repository;

use App\Entity\Place;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Place|null find($id, $lockMode = null, $lockVersion = null)
 * @method Place|null findOneBy(array $criteria, array $orderBy = null)
 * @method Place[]    findAll()
 * @method Place[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Place::class);
    }

    public function delete(Place $place): void
    {
        $this->getEntityManager()->remove($place);
        $this->getEntityManager()->flush();
    }

    public function save(Place $place): void
    {
        $this->getEntityManager()->persist($place);
        $this->getEntityManager()->flush();
    }

    public function topRequestor(): array
    {
        return $this->getEntityManager()
                ->createQuery("SELECT sum(n.amount) as amount, p.name
                                    FROM App\Entity\Needs n, App\Entity\Place p
                                    WHERE p.id=n.place GROUP BY n.place ORDER BY amount DESC")
                ->execute();
    }
}
