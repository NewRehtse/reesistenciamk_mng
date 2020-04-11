<?php

namespace App\DataFixtures;

use App\Persistence\Doctrine\Entity\Address;
use App\Persistence\Doctrine\Entity\Maker;
use App\Persistence\Doctrine\Entity\Needs;
use App\Persistence\Doctrine\Entity\Place;
use App\Persistence\Doctrine\Entity\Task;
use App\Persistence\Doctrine\Entity\Thing;
use App\Persistence\Doctrine\Entity\User;
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
        $address = new Address();
        $address->setId(1);
        $address->setCity('Burgos');
        $address->setAddress1('Avenida Reyes Católicos');
        $address->setPostalCode('09004');

        $address2 = new Address();
        $address2->setId(1);
        $address2->setCity('Madrid');
        $address2->setAddress1('Plaza Mayor, 3');
        $address2->setPostalCode('28001');

        $address3 = new Address();
        $address3->setId(1);
        $address3->setCity('Burgos');
        $address3->setAddress1('Calle Vitoria, 3');
        $address3->setPostalCode('09001');

        $manager->persist($address);
        $manager->persist($address2);
        $manager->persist($address3);

        $user = new User();
        $user->setId(1);
        $user->setEmail('mk@makers.es');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'mk'));
        $user->setRoles(['ROLE_USER']);
        $user->setAddress($address2);

        $maker = new Maker();
        $maker->setId(1);
        $maker->setUser($user);
        $maker->setPrinter('HP CFGBD 301');
        $user->setMaker($maker);

        $manager->persist($maker);
        $manager->persist($user);

        $user2 = new User();
        $user2->setId(2);
        $user2->setEmail('admin@makers.es');
        $user2->setPassword($this->passwordEncoder->encodePassword($user, 'admin'));
        $user2->setRoles(['ROLE_ADMIN']);
        $maker2 = new Maker();
        $maker2->setId(21);
        $maker2->setUser($user2);
        $maker2->setPrinter('HP CFGBD 301');
        $user2->setMaker($maker2);
        $manager->persist($user2);

        $place = new Place();
        $place->setId(1);
        $place->setName('HUBU');
        $place->setAddress($address);
        $place->setOwner($user2);
        $manager->persist($place);

        $place = new Place();
        $place->setId(2);
        $place->setName('RESI');
        $place->setAddress($address2);
        $manager->persist($place);

        $thing = new Thing();
        $thing->setId(1);
        $thing->setType('Mascarilla');
        $thing->setDescription('sencilla');
        $thing->setModel('sencilla');
        $thing->setOwner($user2);
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
        $task->setStatus(Task::STATUS_DELIVERED);
        $task->setDeliveryType(Task::DELIVER_TYPE_COLLECT);
        $task->setExtra('extra todo');
        $task->setMaker($maker);
        $manager->persist($task);

        $manager->flush();
    }
}
