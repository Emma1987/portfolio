<?php

namespace App\Controller;

use App\Entity\InterviewPrompt;
use App\Entity\Project;
use App\Entity\Tool;
use App\Form\ProjectFilterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale}', requirements: ['_locale' => 'en|fr'], defaults: ['_locale' => 'fr'])]
class PublicController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    #[Route('/', name: 'em_portfolio_home')]
    public function home(): Response
    {
        return $this->render('public/index.html.twig', []);
    }

    #[Route('/projects', name: 'em_portfolio_projects')]
    public function projects(): Response
    {
        $filterForm = $this->createForm(ProjectFilterType::class);

        return $this->render('public/projects.html.twig', [
            'projects' => $this->entityManager->getRepository(Project::class)->findAll(),
            'form' => $filterForm,
        ]);
    }

    #[Route('/about', name: 'em_portfolio_about')]
    public function about(): Response
    {
        return $this->render('public/about.html.twig', [
            'tools' => $this->entityManager->getRepository(Tool::class)->findAll(),
        ]);
    }

    #[Route('/contact', name: 'em_portfolio_contact')]
    public function contact(): Response
    {
        return $this->render('public/contact.html.twig', []);
    }

    #[Route('/recruiter-game', name: 'em_portfolio_recruiter_game')]
    public function recruiterGame(): Response
    {
        return $this->render('public/recruiter_game.html.twig', [
            'questions' => $this->entityManager->getRepository(InterviewPrompt::class)->findAll(),
        ]);
    }
}
