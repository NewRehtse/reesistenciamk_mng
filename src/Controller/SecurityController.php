<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class SecurityController extends AbstractController
{
    public function login(Request $request, AuthenticationUtils $authUtils): Response
    {
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * Logout.
     */
    public function logout(): void
    {
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}