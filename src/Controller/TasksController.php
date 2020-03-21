<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class TasksController extends AbstractController
{
    public function list():Response
    {
        return $this->render('tasks/list.html.twig');
    }

}