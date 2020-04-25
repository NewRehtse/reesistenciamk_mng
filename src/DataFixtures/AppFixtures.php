<?php

namespace App\DataFixtures;

use App\Persistence\Doctrine\Entity\Address;
use App\Persistence\Doctrine\Entity\Configuration;
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
        $configuration = new Configuration();
        $manager->persist($configuration);

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

        foreach ($this->getThingData() as  $thingData) {
            $thing = new Thing();
            $thing->setId($thingData['id']);
            $thing->setType($thingData['type']);
            $thing->setDescription($thingData['description']);
            $thing->setModel($thingData['model']);
            $thing->setPhotoUrl($thingData['photo']);
            $thing->setOtherUrl($thingData['other']);
            $thing->setUrlThingiverse($thingData['url']);
            $thing->setOwner($user2);
            $manager->persist($thing);
        }

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

    private function getThingData(): array
    {
        return [
                [
                        'id' => 1,
                        'type' => 'Soporte',
                        'model' => 'Mechanical Quick Grab/Release Phone Stand',
                        'description' => 'This is a great design that I\'ve dialed in and got working very well. Looks great and is very functional. This stand allows multiple viewing angles and best of all has a quick grab/release mechanism that grabs and locks the phone in when you let go and releases when you pick up the phone.',
                        'url' => 'https://www.thingiverse.com/thing:3681512',
                        'other' => 'https://amzn.to/2K4XJ6b',
                        'photo' => 'https://cdn.thingiverse.com/renders/be/bd/f0/11/b4/c822025c9886c083843e583df89e0770_preview_featured.jpg',
                ],
                [
                        'id' => 2,
                        'type' => 'Adorno',
                        'model' => 'Flying Sea Turtle',
                        'description' => '<p>When the film spreads through the webs, it shows that a plastic straw pulled out from the poor turtle\'s nostrils, which used its misfortune to slightly change the world.</p>
<p>Many people have begun to take the initiative about stop using the plastic straws, and some chain restaurants have no longer offer the plastic straws to their customers. As the matter of fact, it is not just straws that would harm the turtles in the ocean! Marine debris, plastic bags, fishing hooks, abandoned fishing nets, etc. also lead turtles to death. The rapid development of science and technology have created many substances that should not exist in our nature; Unfortunately, most species are not evolving fast enough to catch up with us. These sea turtles are typical examples. They don’t have enough ability to distinguish the jellyfish they love from those plastic bags that might kill them, therefore, It’s much more important that we should think about our nature when we’re trying to get anything further progressed.</p>',
                        'url' => 'https://www.thingiverse.com/thing:3758189',
                        'other' => 'https://www.amazon.com/SPIDER-MAKER-Matte-Printer-Filament/dp/B07HX9DY9F',
                        'photo' => 'https://cdn.thingiverse.com/renders/73/62/72/49/3e/6cb2ce76709abb442f2cdc1a41a31c4e_preview_featured.jpg',
                ],
                [
                        'id' => 3,
                        'type' => 'Adorno',
                        'model' => 'Baby yoda',
                        'description' => 'A cute lil baby Yoda just in time for Christmas! I sculpted this in ZBrush because he\'s just so darned adorable. Print him up for all your friends that are obsessed with this little guy- I know I will be printing quite a few...
Don\'t forget to "post a make" and follow if you decide to print one! We would love to see all of your end products! You can also look forward to other quality prints coming soon!',
                        'url' => 'https://www.thingiverse.com/thing:4038181',
                        'other' => '',
                        'photo' => 'https://cdn.thingiverse.com/assets/93/fb/97/70/c6/featured_preview_babyYoda3.jpg',
                ],
                [
                        'id' => 4,
                        'type' => 'Soporte',
                        'model' => 'Oculus Rift Sensor mount',
                        'description' => 'Sensor mount for the Oculus Rift CV1 sensor. Use a 3M command strip to wall mount your sensor for improved field of view.
I designed the hole for the stem of the sensor too snug and used a flat head screw driver to scrape it out to the precise size I wanted.',
                        'url' => 'https://www.thingiverse.com/thing:1754041',
                        'other' => '',
                        'photo' => 'https://cdn.thingiverse.com/renders/ce/d5/ec/b4/9d/0fd1dff9e4bd6d1d0c531d146e544616_preview_featured.jpg',
                ],
            [
                    'id' => 5,
                    'type' => 'Soporte',
                'model' => 'Oculus Rift Sensor mount',
                'description' => 'Sensor mount for the Oculus Rift CV1 sensor. Use a 3M command strip to wall mount your sensor for improved field of view.
I designed the hole for the stem of the sensor too snug and used a flat head screw driver to scrape it out to the precise size I wanted.',
                'url' => 'https://www.thingiverse.com/thing:1754041',
                'other' => '',
                'photo' => 'https://cdn.thingiverse.com/renders/ce/d5/ec/b4/9d/0fd1dff9e4bd6d1d0c531d146e544616_preview_featured.jpg',
            ],
//            [
//                'type' => '',
//                'model' => '',
//                'description' => '',
//                'url' => '',
//                'other' => '',
//                'photo' => '',
//            ],
        ];
    }
}
