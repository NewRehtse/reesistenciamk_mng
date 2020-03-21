<?php

namespace App\DataFixtures;

use App\Entity\Needs;
use App\Entity\Place;
use App\Entity\Task;
use App\Entity\Thing;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setId(1);
        $user->setEmail('mk@makers.es');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'mk'));
        $user->setRoles(['ROLE_USER']);

        $manager->persist($user);

        $user2 = new User();
        $user2->setId(2);
        $user2->setEmail('admin@makers.es');
        $user2->setPassword($this->passwordEncoder->encodePassword($user, 'admin'));
        $user2->setRoles(['ROLE_ADMIN']);
        $manager->persist($user2);

        $manager->flush();
        $place = new Place();
        $place->setId(1);
        $place->setName('HUBU');
        $place->setAddress('Burgos');
        $place->setCity('Burgos');
        $place->setPostalCode('09007');
        $manager->persist($place);

        $place = new Place();
        $place->setId(2);
        $place->setName('RESI');
        $place->setAddress('Burgos');
        $place->setCity('Burgos');
        $place->setPostalCode('09007');
        $manager->persist($place);

        $thing = new Thing();
        $thing->setId(1);
        $thing->setDescription('sencilla');
        $thing->setModel('sencilla');
        $manager->persist($thing);

        $needs = new Needs();
        $needs->setId(1);
        $needs->setPlace($place);
        $needs->setThing($thing);
        $needs->setAmount(10);
        $manager->persist($needs);

        $task = new Task();
        $task->setId(1);
        $task->setThing($thing);
        $task->setPlace($place);
        $task->setStatus(Task::STATUS_DELIVERING);
        $task->setDeliveryDate(new \DateTime());
        $task->setDeliveryType(Task::DELIVER_TYPE_COLLECT);
        $task->setExtra('extra todo');
        $task->setMaker($user);
        $manager->persist($task);

        $manager->flush();
    }
}
