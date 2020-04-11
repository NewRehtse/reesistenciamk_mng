<?php

namespace App\Persistence\Doctrine\Repository;

use App\Persistence\Doctrine\Entity\SerialNumber;
use App\Persistence\Doctrine\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class SerialNumberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SerialNumber::class);
    }

    public function save(SerialNumber $place): void
    {
        $this->getEntityManager()->persist($place);
        $this->getEntityManager()->flush();
    }

    /**
     * @return SerialNumber[]
     */
    public function createSerialNumers(Task $task): array
    {
        $serialNumbers = [];
        for ($i = 0; $i <= $task->amount(); ++$i) {
            $serialNumber = new SerialNumber();
            $serialNumber->setThing($task->thing());
            $serialNumber->setTask($task);
            $this->save($serialNumber);
            $serialNumbers[] = $serialNumber;
        }

        return $serialNumbers;
    }
}
