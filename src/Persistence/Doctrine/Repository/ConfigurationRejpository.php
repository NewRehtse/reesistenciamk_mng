<?php

namespace App\Persistence\Doctrine\Repository;

use App\Persistence\Doctrine\Entity\Configuration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ConfigurationRejpository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Configuration::class);
    }

    public function get(): Configuration
    {
        $configurations = $this->findAll();

        return \array_pop($configurations);
    }

    public function save(Configuration $configuration): void
    {
        $dbConfiguration = $this->get();
        if (null === $dbConfiguration) {
            $dbConfiguration = new Configuration();
        }
        $dbConfiguration->setUsersCanCreatePrints($configuration->usersCanCreatePrints());
        $this->getEntityManager()->persist($dbConfiguration);
        $this->getEntityManager()->flush();
    }
}
