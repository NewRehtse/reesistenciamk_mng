<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('mk@makers.es');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'mk'));

        $manager->persist($user);

        $user2 = new User();
        $user2->setEmail('admin@makers.es');
        $user2->setPassword($this->passwordEncoder->encodePassword($user, 'admin'));
        $manager->persist($user2);

        $manager->flush();
    }
}
