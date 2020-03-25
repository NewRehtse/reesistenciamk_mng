<?php

namespace App\Repository;

use App\Entity\Task;
use App\Entity\Thing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function delete(Task $task): void
    {
        $this->getEntityManager()->remove($task);
        $this->getEntityManager()->flush();
    }

    public function save(Task $task): void
    {
        $this->getEntityManager()->persist($task);
        $this->getEntityManager()->flush();
    }

    public function howManyThingsDelivered(Thing $thing): int
    {
        $tasks = $this->findBy(['thing' => $thing]);
        $collectedOrDelivered = 0;

        foreach ($tasks as $task) {
            if (Task::STATUS_COLLECTED === $task->status() || Task::STATUS_DELIVERED === $task->status()) {
                $collectedOrDelivered += $task->amount();
            }
        }

        return $collectedOrDelivered;
    }

    public function howManyThingsByStatus(int $status): int
    {
        $tasks = $this->findBy(['status' => $status]);

        $howMany = 0;
        foreach ($tasks as $task) {
            if ($status === $task->status()) {
                $howMany += $task->amount();
            }
        }

        return $howMany;
    }
}
