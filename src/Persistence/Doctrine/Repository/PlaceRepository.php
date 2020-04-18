<?php

namespace App\Persistence\Doctrine\Repository;

use App\Persistence\Doctrine\Entity\Place;
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

    /**
     * @return Place[]
     */
    public function topRequestor(): array
    {
        return $this->createQueryBuilder('place')
                ->select('sum(needs.amount) as amount, place.name')
                ->innerJoin('place.needs', 'needs')
                ->groupBy('needs.place')
                ->getQuery()
                ->getResult();
    }

    public function findByName(string $name): ?Place
    {
        return $this->findOneBy(['name' => $name]);
    }
}
