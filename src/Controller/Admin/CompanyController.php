<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use App\Form\CompanySortType;
use App\Form\CompanyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CompanyController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    #[Route(path: '/backstage/companies', name: 'em_portfolio_company_list')]
    public function list(Request $request): Response
    {
        $companies = $this->entityManager->getRepository(Company::class)->findBy([], ['position' => 'ASC']);
        $company = new Company();

        $form = $this->createForm(CompanyType::class, $company);
        $sortForm = $this->createForm(CompanySortType::class, ['companies' => $companies]);
        $sortForm->handleRequest($request);

        if ($sortForm->isSubmitted()) {
            if (!$sortForm->isValid()) {
                $this->addFlash('error', 'Obviously, not what you expected when you tried sorting companies...');
            } else {
                $this->entityManager->flush();

                $this->addFlash('success', '404 errors feared you. Success found you.');
            }

            return $this->redirectToRoute('em_portfolio_company_list');
        }

        return $this->render('admin/companies.html.twig', [
            'companies' => $companies,
            'form' => $form,
            'sort_form' => $sortForm,
        ]);
    }

    #[Route(path: '/backstage/company/add', name: 'em_portfolio_company_add', methods: [Request::METHOD_POST])]
    public function add(Request $request): Response
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->addFlash('error', 'Obviously, not what you expected when you tried adding a company...');
            } else {
                if ($this->entityManager->getRepository(Company::class)->findOneBy(['name' => $company->getName()])) {
                    $this->addFlash('error', 'Don\'t remember you already created this one?');

                    return $this->redirectToRoute('em_portfolio_company_list');
                }

                $count = $this->entityManager->getRepository(Company::class)->count();
                $company->setPosition($count + 1);

                $this->entityManager->persist($company);
                $this->entityManager->flush();

                $this->addFlash('success', sprintf('New company "%s" created!', $company->getName()));
            }
        }

        return $this->redirectToRoute('em_portfolio_company_list');
    }

    #[Route(path: '/backstage/company/delete/{id}', name: 'em_portfolio_company_delete')]
    public function delete(Company $company): Response
    {
        $this->entityManager->remove($company);
        $this->entityManager->flush();

        $this->addFlash('success', 'You make CRUD look good.');

        return $this->redirectToRoute('em_portfolio_company_list');
    }
}
