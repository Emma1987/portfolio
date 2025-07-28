<?php

namespace App\Controller\Admin;

use App\Entity\InterviewPrompt;
use App\Form\InterviewPromptType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecruiterGameController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    #[Route(path: '/backstage/recruiter-game', name: 'em_portfolio_recruiter_game_index')]
    public function index(): Response
    {
        return $this->render('admin/recruiter-game.html.twig', [
            'questions' => $this->entityManager->getRepository(InterviewPrompt::class)->findAll(withHidden: true), // @phpstan-ignore-line
        ]);
    }

    #[Route(path: '/backstage/recruiter-game/add', name: 'em_portfolio_recruiter_game_add')]
    #[Route(path: '/backstage/recruiter-game/edit/{id}', name: 'em_portfolio_recruiter_game_edit')]
    public function add(Request $request, ?InterviewPrompt $interviewPrompt): Response
    {
        if ('em_portfolio_recruiter_game_add' === $request->attributes->get('_route')) {
            $interviewPrompt = new InterviewPrompt();
        }

        $form = $this->createForm(InterviewPromptType::class, $interviewPrompt);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->entityManager->persist($interviewPrompt);
                $this->entityManager->flush();

                $this->addFlash('success', 'Perfect 🚀 Now back to pretending to be productive.');
            } else {
                $this->addFlash('error', 'Error 418: I\'m a teapot. Just kidding — something went wrong.');
            }

            return $this->redirectToRoute('em_portfolio_recruiter_game_index');
        }

        return $this->render('admin/recruiter-game/add.html.twig', [
            'form' => $form,
            'edit' => 'em_portfolio_recruiter_game_edit' === $request->attributes->get('_route'),
        ]);
    }

    #[Route(path: '/backstage/recruiter-game/preview/{id}', name: 'em_portfolio_recruiter_game_preview')]
    public function preview(InterviewPrompt $interviewPrompt): Response
    {
        return $this->render('admin/recruiter-game/preview.html.twig', [
            'question' => $interviewPrompt,
        ]);
    }

    #[Route(path: '/backstage/recruiter-game/highlight/{id}', name: 'em_portfolio_recruiter_game_highlight')]
    public function highlight(InterviewPrompt $interviewPrompt): Response
    {
        $toFeature = !$interviewPrompt->isFeatured();

        $interviewPrompt->setFeatured($toFeature);
        $this->entityManager->flush();

        $message = $toFeature ? 'Interview prompt highlighted! Keep up the good work!' : 'This interview prompt is no longer in the spotlight! Keep up the good work!';
        $this->addFlash('success', $message);

        return $this->redirectToRoute('em_portfolio_recruiter_game_index');
    }

    #[Route(path: '/backstage/recruiter-game/hide/{id}', name: 'em_portfolio_recruiter_game_hide')]
    public function hide(InterviewPrompt $interviewPrompt): Response
    {
        $toHide = !$interviewPrompt->isHidden();

        $interviewPrompt
            ->setHidden($toHide)
            ->setFeatured(false)
        ;
        $this->entityManager->flush();

        $message = $toHide ? 'Interview prompt hidden! Nailed it! 🎯' : 'This interview prompt is no longer hidden! Nailed it! 🎯';
        $this->addFlash('success', $message);

        return $this->redirectToRoute('em_portfolio_recruiter_game_index');
    }

    #[Route(path: '/backstage/recruiter-game/delete/{id}', name: 'em_portfolio_recruiter_game_delete')]
    public function delete(InterviewPrompt $interviewPrompt): Response
    {
        $this->entityManager->remove($interviewPrompt);
        $this->entityManager->flush();

        $this->addFlash('success', 'Data flushed. Like a good cache clear.');

        return $this->redirectToRoute('em_portfolio_recruiter_game_index');
    }
}
