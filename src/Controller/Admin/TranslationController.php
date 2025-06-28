<?php

namespace App\Controller\Admin;

use App\Form\TranslationsType;
use App\Service\TranslationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TranslationController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private TranslationService $translationService) {}

    #[Route(path: '/backstage/translations', name: 'em_portfolio_translations')]
    public function translations(Request $request): Response
    {
        $translations = $this->translationService->getOrderedTranslations();

        $form = $this->createForm(TranslationsType::class, ['translations' => $translations]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->addFlash('error', 'Hmmm… something hiccupped during the translation update. Let\'s try that spell again?');
            } else {
                $this->entityManager->flush();

                $this->addFlash('success', 'Your action succeeded, unlike your last attempt at adulting.');
            }

            return $this->redirectToRoute('em_portfolio_translations');
        }

        return $this->render('admin/translations.html.twig', [
            'form' => $form,
        ]);
    }
}
