<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use App\Entity\InterviewPrompt;
use App\Entity\Project;
use App\Entity\Tag;
use App\Entity\Technology;
use App\Entity\Tool;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    #[Route(path: '/backstage', name: 'em_portfolio_backend')]
    public function login(): Response
    {
        return $this->render('admin/index.html.twig', [
            'project_count' => $this->entityManager->getRepository(Project::class)->count(),
            'technology_count' => $this->entityManager->getRepository(Technology::class)->count(),
            'tag_count' => $this->entityManager->getRepository(Tag::class)->count(),
            'company_count' => $this->entityManager->getRepository(Company::class)->count(),
            'tool_count' => $this->entityManager->getRepository(Tool::class)->count(),
            'question_count' => $this->entityManager->getRepository(InterviewPrompt::class)->count(),
        ]);
    }
}
