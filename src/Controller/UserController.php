<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\CreateUserType;
use App\Form\Type\EditProfile;
use App\Form\Type\EditUserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class UserController extends AbstractController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function profile(): Response
    {
        $user = $this->getUser();

        return $this->render('users/profile.html.twig', ['user' => $user]);
    }

    public function profileEdit(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $this->getUser();
        $currentRoles = $user->getRoles();

        $form = $this->createForm(EditProfile::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setRoles($currentRoles); //Los roles no se actualizan

            $this->userRepository->save($user);

            return $this->redirectToRoute('users.profile');
        }

        return $this->render('users/profile-edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function adminUsers(): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('tasks');
        }

        $users = $this->userRepository->findAll();

        return $this->render('users/admin-users.html.twig', ['users' => $users]);
    }

    public function adminUsersEdit(Request $request, int $userId): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('tasks');
        }

        $user = $this->userRepository->find($userId);

        $form = $this->createForm(EditUserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();

            $this->userRepository->save($user);

            return $this->redirectToRoute('users.admin');
        }

        return $this->render('users/admin-users-create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function adminUsersCreate(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('tasks');
        }

        $user = new User();

        $form = $this->createForm(CreateUserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $this->userRepository->save($user);

            return $this->redirectToRoute('users.admin');
        }

        return $this->render('users/admin-users-create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function adminUsersDetail(Request $request, int $userId): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('tasks');
        }

        $user = $this->userRepository->find($userId);
        if (null === $user) {
            return $this->redirectToRoute('users.admin');
        }

        return $this->render('users/admin-users-detail.html.twig', ['user' => $user]);
    }
}
