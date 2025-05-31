<?php

namespace App\Controller\Admin;

use App\Entity\Technology;
use App\Form\TechnologySortType;
use App\Form\TechnologyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TechnologyController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    #[Route(path: '/backstage/technologies', name: 'em_portfolio_technologies_list')]
    public function list(): Response
    {
        $technologies = $this->entityManager->getRepository(Technology::class)->findBy([], ['position' => 'ASC']);
        $technology = new Technology();

        $form = $this->createForm(TechnologyType::class, $technology);
        $sortForm = $this->createForm(TechnologySortType::class, ['technologies' => $technologies]);

        return $this->render('admin/technologies/list.html.twig', [
            'technologies' => $technologies,
            'form' => $form,
            'sortForm' => $sortForm,
        ]);
    }

    #[Route(path: '/backstage/technologies/add', name: 'em_portfolio_technologies_add', methods: [Request::METHOD_POST])]
    public function add(Request $request): Response
    {
        $technology = new Technology();
        $form = $this->createForm(TechnologyType::class, $technology);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->addFlash('error', 'Obviously, not what you expected when you tried adding a technology...');
            } else {
                if ($this->entityManager->getRepository(Technology::class)->findOneBy(['name' => $technology->getName()])) {
                    $this->addFlash('error', 'Don\'t remember you already created this one?');

                    return $this->redirectToRoute('em_portfolio_technologies_list');
                }

                $count = $this->entityManager->getRepository(Technology::class)->count();
                $technology->setPosition($count + 1);

                $this->entityManager->persist($technology);
                $this->entityManager->flush();

                $this->addFlash('success', sprintf('New technology "%s" created!', $technology->getName()));
            }
        }

        return $this->redirectToRoute('em_portfolio_technologies_list');
    }

    #[Route(path: '/backstage/technologies/sort', name: 'em_portfolio_technologies_sort', methods: [Request::METHOD_POST])]
    public function sort(Request $request): Response
    {
        $technologies = $this->entityManager->getRepository(Technology::class)->findAll();
        $form = $this->createForm(TechnologySortType::class, ['technologies' => $technologies]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->addFlash('error', 'Obviously, not what you expected when you tried sorting technologies...');
            } else {
                $this->entityManager->flush();

                $this->addFlash('success', 'Boom. It worked. You rock.');
            }
        }

        return $this->redirectToRoute('em_portfolio_technologies_list');
    }

    #[Route(path: '/backstage/technologies/delete/{id}', name: 'em_portfolio_technologies_delete')]
    public function delete(Technology $technology): Response
    {
        $this->entityManager->remove($technology);
        $this->entityManager->flush();

        $this->addFlash('success', 'Success. You’ve earned yourself a coffee. Or a nap.');

        return $this->redirectToRoute('em_portfolio_technologies_list');
    }
}
