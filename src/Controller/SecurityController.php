<?php

namespace App\Controller;

use App\Form\Type\RegisterUserType;
use App\Persistence\Doctrine\Entity\Delivery;
use App\Persistence\Doctrine\Entity\Maker;
use App\Persistence\Doctrine\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function login(Request $request, AuthenticationUtils $authUtils): Response
    {
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * Logout.
     */
    public function logout(): void
    {
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

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

            if (\in_array('maker', $user->userType(), true)) {
                $maker = new Maker();
                $user->setMaker($maker);
                $roles[] = 'ROLE_MAKER';
            }
            if (\in_array('delivery', $user->userType(), true)) {
                $delivery = new Delivery();
                $user->setDelivery($delivery);
                $roles[] = 'ROLE_DELIVERY';
            }
            if (\in_array('health', $user->userType(), true)) {
                $roles[] = 'ROLE_HEALTH';
            }

            $user->setRoles($roles);
            // 4) save the User!
            $entityManager->persist($user);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user
            $this->addFlash(
                    'info',
                    'Bienvenido! ya estas registrado.'
            );

            return $this->redirectToRoute('login');
        }

        return $this->render(
                'security/registration.html.twig', [
                'form' => $form->createView(),
        ]);
    }
}
