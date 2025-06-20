<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route(path: '/backstage', name: 'em_portfolio_backend')]
    public function login(): Response
    {
        return $this->render('admin/index.html.twig', []);
    }
}
