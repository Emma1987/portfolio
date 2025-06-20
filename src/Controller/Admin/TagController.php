<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Form\TagSortType;
use App\Form\TagType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TagController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    #[Route(path: '/backstage/tags', name: 'em_portfolio_tag_list')]
    public function list(Request $request): Response
    {
        $tags = $this->entityManager->getRepository(Tag::class)->findBy([], ['position' => 'ASC']);
        $tag = new Tag();

        $form = $this->createForm(TagType::class, $tag);
        $sortForm = $this->createForm(TagSortType::class, ['tags' => $tags]);
        $sortForm->handleRequest($request);

        if ($sortForm->isSubmitted()) {
            if (!$sortForm->isValid()) {
                $this->addFlash('error', 'Obviously, not what you expected when you tried sorting tags...');
            } else {
                $this->entityManager->flush();

                $this->addFlash('success', 'Logged. Saved. Handled. You rock.');
            }

            return $this->redirectToRoute('em_portfolio_tag_list');
        }

        return $this->render('admin/tags.html.twig', [
            'tags' => $tags,
            'form' => $form,
            'sort_form' => $sortForm,
        ]);
    }

    #[Route(path: '/backstage/tag/add', name: 'em_portfolio_tag_add', methods: [Request::METHOD_POST])]
    public function add(Request $request): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->addFlash('error', 'Obviously, not what you expected when you tried adding a tag...');
            } else {
                if ($this->entityManager->getRepository(Tag::class)->findOneBy(['name' => $tag->getName()])) {
                    $this->addFlash('error', 'Don\'t remember you already created this one?');

                    return $this->redirectToRoute('em_portfolio_tag_list');
                }

                $count = $this->entityManager->getRepository(Tag::class)->count();
                $tag->setPosition($count + 1);

                $this->entityManager->persist($tag);
                $this->entityManager->flush();

                $this->addFlash('success', sprintf('New tag "%s" created!', $tag->getName()));
            }
        }

        return $this->redirectToRoute('em_portfolio_tag_list');
    }

    #[Route(path: '/backstage/tag/delete/{id}', name: 'em_portfolio_tag_delete')]
    public function delete(Tag $tag): Response
    {
        $this->entityManager->remove($tag);
        $this->entityManager->flush();

        $this->addFlash('success', 'Twig smiled. Your action worked.');

        return $this->redirectToRoute('em_portfolio_tag_list');
    }
}
