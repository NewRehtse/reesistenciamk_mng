<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class DashboardController extends AbstractController
{
    public function index(Request $request): Response
    {
        return $this->render('dashboard.html.twig', [
        ]);
    }
}
