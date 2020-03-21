<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class HomeController extends AbstractController
{
    public function home(): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirect('admin');
        }
        \var_dump('NO es admin');

        return $this->render('tasks/list.html.twig');
    }

    public function admin(): Response
    {
        \var_dump('admin');

        return $this->render('tasks/list.html.twig');
    }
}
