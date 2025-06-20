<?php

namespace App\Controller\Admin;

use App\Entity\Enum\ToolCategory;
use App\Entity\Tool;
use App\Form\ToolSortType;
use App\Form\ToolType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ToolBoxController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private FormFactoryInterface $formFactory,
    ) {}

    #[Route(path: '/backstage/toolbox', name: 'em_portfolio_backend_toolbox')]
    public function toolBox(Request $request): Response
    {
        $tools = $this->entityManager->getRepository(Tool::class)->findBy([], ['category' => 'ASC', 'position' => 'ASC']);
        $tool = new Tool();

        $form = $this->createForm(ToolType::class, $tool);

        $sortLanguagesForm = $this->createSortForm(ToolCategory::Languages);
        $sortLanguagesForm->handleRequest($request);

        $sortFrameworksForm = $this->createSortForm(ToolCategory::Frameworks);
        $sortFrameworksForm->handleRequest($request);

        $sortToolsForm = $this->createSortForm(ToolCategory::Tools);
        $sortToolsForm->handleRequest($request);

        $sortPracticesForm = $this->createSortForm(ToolCategory::Practices);
        $sortPracticesForm->handleRequest($request);

        foreach ([$sortLanguagesForm, $sortFrameworksForm, $sortToolsForm, $sortPracticesForm] as $sortForm) {
            if ($sortForm->isSubmitted()) {
                if (!$sortForm->isValid()) {
                    $this->addFlash('error', 'Obviously, not what you expected when you tried sorting tools...');
                } else {
                    $this->entityManager->flush();

                    $this->addFlash('success', 'You just did that. Like a boss.');
                }

                return $this->redirectToRoute('em_portfolio_backend_toolbox');
            }
        }

        return $this->render('admin/toolbox.html.twig', [
            'tools' => $tools,
            'form' => $form,
            'sort_languages_form' => $sortLanguagesForm,
            'sort_frameworks_form' => $sortFrameworksForm,
            'sort_tools_form' => $sortToolsForm,
            'sort_practices_form' => $sortPracticesForm,
        ]);
    }

    #[Route(path: '/backstage/tool/add', name: 'em_portfolio_backend_tool_add')]
    public function add(Request $request): Response
    {
        $tool = new Tool();
        $form = $this->createForm(ToolType::class, $tool);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->addFlash('error', 'Obviously, not what you expected when you tried adding a tool...');
            } else {
                if ($this->entityManager->getRepository(Tool::class)->findOneBy(['name' => $tool->getName()])) {
                    $this->addFlash('error', 'Don\'t remember you already created this one?');

                    return $this->redirectToRoute('em_portfolio_backend_toolbox');
                }

                $count = $this->entityManager->getRepository(Tool::class)->count();
                $tool->setPosition($count + 1);

                $this->entityManager->persist($tool);
                $this->entityManager->flush();

                $this->addFlash('success', sprintf('New tool "%s" created!', $tool->getName()));
            }
        }

        return $this->redirectToRoute('em_portfolio_backend_toolbox');
    }

    #[Route(path: '/backstage/tool/delete/{id}', name: 'em_portfolio_backend_tool_delete')]
    public function delete(Tool $tool): Response
    {
        $this->entityManager->remove($tool);
        $this->entityManager->flush();

        $this->addFlash('success', 'Data flushed. Like a good cache clear.');

        return $this->redirectToRoute('em_portfolio_backend_toolbox');
    }

    private function createSortForm(ToolCategory $category): FormInterface
    {
        return $this->formFactory->createNamed('sort_' . strtolower($category->value), ToolSortType::class, [
            'tools' => $this->entityManager->getRepository(Tool::class)->findBy(['category' => $category], ['position' => 'ASC']),
        ]);
    }
}
