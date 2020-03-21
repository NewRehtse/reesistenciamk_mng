<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class HomeController extends AbstractController
{
    public function home():Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            var_dump('es admin');
        }
        else {
            var_dump('NO es admin');
        }

        return $this->render('tasks/list.html.twig');
    }
}