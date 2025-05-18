<?php

namespace App\Controller;

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

    #[Route(path: '/backstage/projects', name: 'em_portfolio_backend_projects')]
    public function projects(): Response
    {
        return $this->render('admin/projects.html.twig', []);
    }

    #[Route(path: '/backstage/toolbox', name: 'em_portfolio_backend_toolbox')]
    public function toolBox(): Response
    {
        return $this->render('admin/toolbox.html.twig', []);
    }
}
