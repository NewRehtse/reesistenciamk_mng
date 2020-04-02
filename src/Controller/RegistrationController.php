<?php

namespace App\Controller;

use App\Entity\Delivery;
use App\Entity\Maker;
use App\Entity\Place;
use App\Entity\User;
use App\Form\Type\RegisterUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class RegistrationController extends AbstractController
{
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(RegisterUserType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // 3) Encode the password (you could also do this via Doctrine listener)
            $entityManager = $this->getDoctrine()->getManager();
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $roles = ['ROLE_USER'];
            $maker = new Maker();
            $user->setMaker($maker);
            $roles[] = 'ROLE_MAKER';
            $entityManager->persist($maker);
            if (\in_array('delivery', $user->userType(), true)) {
                $delivery = new Delivery();
                $user->setDelivery($delivery);
                $roles[] = 'ROLE_DELIVERY';
                $entityManager->persist($delivery);
            }
            if (\in_array('place', $user->userType(), true)) {
                $place = new Place();
                $user->setPlace($place);
                $entityManager->persist($place);
                $roles[] = 'ROLE_PLACE';
            }

            $user->setRoles($roles);
            // 4) save the User!
            $entityManager->persist($user);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('login');
        }

        return $this->render(
            'security/registration.html.twig', [
                'form' => $form->createView(),
            ]);
    }
}
