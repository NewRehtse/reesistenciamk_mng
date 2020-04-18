<?php

namespace App\Persistence\Doctrine;

use App\Persistence\Doctrine\Entity\Configuration;
use App\Persistence\Doctrine\Entity\Maker;
use App\Persistence\Doctrine\Entity\Needs;
use App\Persistence\Doctrine\Entity\Place;
use App\Persistence\Doctrine\Entity\RequestCollect;
use App\Persistence\Doctrine\Entity\SerialNumber;
use App\Persistence\Doctrine\Entity\Task;
use App\Persistence\Doctrine\Entity\Thing;
use App\Persistence\Doctrine\Entity\User;
use App\Persistence\Doctrine\Repository\ConfigurationRejpository;
use App\Persistence\Doctrine\Repository\NeedsRepository;
use App\Persistence\Doctrine\Repository\PlaceRepository;
use App\Persistence\Doctrine\Repository\RequestCollectRepository;
use App\Persistence\Doctrine\Repository\SerialNumberRepository;
use App\Persistence\Doctrine\Repository\TaskRepository;
use App\Persistence\Doctrine\Repository\ThingRepository;
use App\Persistence\Doctrine\Repository\UserRepository;

class GeneralDoctrineRepository
{
    /** @var TaskRepository */
    private $taskRepository;

    /** @var UserRepository */
    private $userRepository;

    /** @var PlaceRepository */
    private $placeRepository;

    /** @var ThingRepository */
    private $thingRepository;

    /** @var NeedsRepository */
    private $needsRepository;

    /** @var RequestCollectRepository */
    private $requestCollectRepository;

    /** @var SerialNumberRepository */
    private $serialNumberRepository;

    /** @var ConfigurationRejpository */
    private $configurationRepository;

    public function __construct(
            TaskRepository $taskRepository,
            UserRepository $userRepository,
            PlaceRepository $placeRepository,
            ThingRepository $thingRepository,
            NeedsRepository $needsRepository,
            RequestCollectRepository $requestCollectRepository,
            SerialNumberRepository $serialNumberRepository,
            ConfigurationRejpository $configurationRejpository
    ) {
        $this->taskRepository = $taskRepository;
        $this->userRepository = $userRepository;
        $this->placeRepository = $placeRepository;
        $this->thingRepository = $thingRepository;
        $this->needsRepository = $needsRepository;
        $this->requestCollectRepository = $requestCollectRepository;
        $this->serialNumberRepository = $serialNumberRepository;
        $this->configurationRepository = $configurationRejpository;
    }

    public function howManyThingsDelivered(Thing $thing): int
    {
        return $this->taskRepository->howManyThingsDelivered($thing);
    }

    public function howManyThingsByIdAndStatus(Thing $thing, int $status): int
    {
        return $this->taskRepository->howManyThingsByIdAndStatus($thing, $status);
    }

    public function howManyThingsByStatus(int $status): int
    {
        return $this->taskRepository->howManyThingsByStatus($status);
    }

    /**
     * @return Task[]
     */
    public function findTasksByMakerAndStatus(Maker $maker, int $status): array
    {
        return $this->taskRepository->findByMakerAndStatus($maker, $status);
    }

    /**
     * @return Task[]
     */
    public function findTasksByMaker(Maker $maker): array
    {
        return $this->taskRepository->findByMaker($maker);
    }

    /**
     * @return Task[]
     */
    public function findTasksByStatus(int $status): array
    {
        return $this->taskRepository->findByStatus($status);
    }

    /**
     * @return User[]
     */
    public function topMakers(): array
    {
        return $this->userRepository->topMakers();
    }

    /**
     * @return Place[]
     */
    public function topRequestor(): array
    {
        return $this->placeRepository->topRequestor();
    }

    /**
     * @return Thing[]
     */
    public function topNeeded(): array
    {
        return $this->thingRepository->topNeeded();
    }

    /**
     * @return Place[]
     */
    public function getAllPlaces(): array
    {
        return $this->placeRepository->findAll();
    }

    /**
     * @return Thing[]
     */
    public function getAllThings(): array
    {
        return $this->thingRepository->findAll();
    }

    public function findPlace(int $placeId): ?Place
    {
        return $this->placeRepository->find($placeId);
    }

    public function findPlaceByName(string $name): ?Place
    {
        return $this->placeRepository->findByName($name);
    }

    public function findThing(int $thingId): ?Thing
    {
        return $this->thingRepository->find($thingId);
    }

    /**
     * @return Task[]
     */
    public function getAllTasks(): array
    {
        return $this->taskRepository->findAll();
    }

    public function findTask(int $taskId): ?Task
    {
        return $this->taskRepository->find($taskId);
    }

    public function saveRequestCollect(RequestCollect $requestCollect): void
    {
        $this->requestCollectRepository->save($requestCollect);
    }

    public function savePlace(Place $place): void
    {
        $this->placeRepository->save($place);
    }

    public function deletePlace(Place $place): void
    {
        $this->placeRepository->delete($place);
    }

    public function findNeedsByThingAndPlace(Thing $thing, Place $place): ?Needs
    {
        return $this->needsRepository->findOneBy(['thing' => $thing, 'place' => $place]);
    }

    /**
     * @return Needs[]
     */
    public function findNeedsByPlace(Place $place): array
    {
        return $this->needsRepository->findBy(['place' => $place]);
    }

    public function findNeeds(int $needsId): ?Needs
    {
        return $this->needsRepository->find($needsId);
    }

    public function saveNeeds(Needs $needs): void
    {
        $this->needsRepository->save($needs);
    }

    /**
     * @return SerialNumber[]
     */
    public function findTaskSerialNumbers(Task $task): array
    {
        return $this->serialNumberRepository->findBy(['task' => $task]);
    }

    public function createTaskAndSerialNumbers(Task $task): void
    {
        $this->taskRepository->save($task);
        $this->serialNumberRepository->createserialnumers($task);
    }

    public function saveTask(Task $task): void
    {
        $this->taskRepository->save($task);
    }

    public function saveThing(Thing $thing): void
    {
        $this->thingRepository->save($thing);
    }

    public function removeThing(Thing $thing): void
    {
        $this->thingRepository->delete($thing);
    }

    public function saveUser(User $user): void
    {
        $this->userRepository->save($user);
    }

    public function refreshUser(User $user): void
    {
        $this->userRepository->refreshUser($user);
    }

    /**
     * @return User[]
     */
    public function getAllUsers(): array
    {
        return $this->userRepository->findAll();
    }

    public function findUser(int $userId): ?User
    {
        return $this->userRepository->find($userId);
    }

    public function getConfiguration(): Configuration
    {
        return $this->configurationRepository->get();
    }

    public function saveConfiguration(Configuration $configuration): void
    {
        $this->configurationRepository->save($configuration);
    }
}
