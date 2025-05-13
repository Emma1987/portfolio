<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale}', requirements: ['_locale' => 'en|fr'], defaults: ['_locale' => 'fr'])]
class HomeController extends AbstractController
{
    #[Route('/', name: 'em_portfolio_home')]
    public function home(): Response
    {
        return $this->render('public/index.html.twig', []);
    }

    #[Route('/projects', name: 'em_portfolio_projects')]
    public function projects(): Response
    {
        return $this->render('public/projects.html.twig', []);
    }

    #[Route('/about', name: 'em_portfolio_about')]
    public function about(): Response
    {
        return $this->render('public/about.html.twig', []);
    }

    #[Route('/contact', name: 'em_portfolio_contact')]
    public function contact(): Response
    {
        return $this->render('public/contact.html.twig', []);
    }
}
