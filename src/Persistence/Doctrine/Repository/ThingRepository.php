<?php

namespace App\Persistence\Doctrine\Repository;

use App\Persistence\Doctrine\Entity\Thing;
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

    /**
     * @return Thing[]
     */
    public function topNeeded(): array
    {
        return $this->createQueryBuilder('thing')
                ->select('sum(needs.amount) as amount, thing')
                ->innerJoin('thing.needs', 'needs')
                ->groupBy('needs.thing')
                ->getQuery()
                ->getResult();
        /*
        return $this->getEntityManager()
                ->createQuery("SELECT sum(n.amount) as amount, t
                                    FROM App\Persistence\Doctrine\Entity\Needs n, App\Persistence\Doctrine\Entity\Thing t
                                    WHERE t.id=n.thing GROUP BY n.thing ORDER BY amount DESC")
                ->execute();
        */
    }
}
