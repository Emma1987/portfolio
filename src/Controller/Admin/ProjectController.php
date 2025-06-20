<?php

namespace App\Controller\Admin;

use App\Entity\Picture;
use App\Entity\Project;
use App\Form\ProjectType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    #[Route(path: '/backstage/projects', name: 'em_portfolio_project_list')]
    public function list(): Response
    {
        return $this->render('admin/projects.html.twig', [
            'projects' => $this->entityManager->getRepository(Project::class)->findAll(),
        ]);
    }

    #[Route(path: '/backstage/project/add', name: 'em_portfolio_project_add')]
    public function add(Request $request): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->entityManager->persist($project);
                $this->entityManager->flush();

                $this->addFlash('success', 'Project created! Look at you go, building things like a pro. 🚀');
            } else {
                $this->addFlash('error', 'Oops! We couldn\'t save your brilliant idea. Want to try that again? 🔧🐞');
            }

            return $this->redirectToRoute('em_portfolio_project_list');
        }

        return $this->render('admin/project/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route(path: '/backstage/project/edit/{id}', name: 'em_portfolio_project_edit')]
    public function edit(Request $request, Project $project): Response
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->entityManager->flush();

                $this->addFlash('success', 'Changes saved! It\'s now 27% better (scientifically proven). 🧠💾');
            } else {
                $this->addFlash('error', 'Something went wrong. Your project remains exactly as it was... for now. 🕰️');
            }

            return $this->redirectToRoute('em_portfolio_project_list');
        }

        return $this->render('admin/project/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route(path: '/backstage/project/delete/{id}', name: 'em_portfolio_project_delete')]
    public function delete(Project $project): Response
    {
        $this->entityManager->remove($project);
        $this->entityManager->flush();

        $this->addFlash('success', 'Project deleted. Ghosted. 👻');

        return $this->redirectToRoute('em_portfolio_project_list');

    }

    #[Route(path: '/backstage/picture/delete/{id}', name: 'em_portfolio_picture_delete')]
    public function removePicture(Picture $picture): Response
    {
        $project = $picture->getProject();

        $this->entityManager->remove($picture);
        $this->entityManager->flush();

        $this->addFlash('success', 'Poof! The picture\'s gone. No going back now... 🧙️✨');

        return $this->redirectToRoute('em_portfolio_project_edit', ['id' => $project->getId()]);

    }
}
