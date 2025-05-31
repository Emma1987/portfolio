<?php

namespace App\Tests\Controller\Admin;

use App\Entity\Company;
use App\Tests\AdminWebTestCase;

class CompanyControllerTest extends AdminWebTestCase
{
    public function testRenderCompaniesIsSuccessful()
    {
        $this->client->request('GET', '/backstage/companies');
        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('.companies-card');
        $this->assertSelectorExists('form[name=company]');
        $this->assertSelectorExists('form[name=company_sort]');
    }

    public function testAddCompany(): void
    {
        $savedCompanies = $this->entityManager->getRepository(Company::class)->count();
        $crawler = $this->client->request('GET', '/backstage/companies');

        $form = $crawler->filter('form[name=company]')->form();
        $form['company[name]'] = 'Infinite Loop Hell';

        $this->client->submit($form);

        $this->assertResponseRedirects('/backstage/companies');
        $this->client->followRedirect();

        $this->assertSelectorExists('.alert-success');
        $this->assertSelectorTextContains('.alert-success', 'New company "Infinite Loop Hell" created!');

        $company = $this->entityManager->getRepository(Company::class)->findOneBy(['name' => 'Infinite Loop Hell']);
        $this->assertNotNull($company);
        $this->assertSame($savedCompanies + 1, $company->getPosition());
    }

    public function testAddAlreadyExistingCompany(): void
    {
        $companies = $this->entityManager->getRepository(Company::class)->findAll();
        $existingCompany = array_shift($companies);

        $crawler = $this->client->request('GET', '/backstage/companies');

        $form = $crawler->filter('form[name=company]')->form();
        $form['company[name]'] = $existingCompany->getName();

        $this->client->submit($form);

        $this->assertResponseRedirects('/backstage/companies');
        $this->client->followRedirect();

        $this->assertSelectorExists('.alert-danger');
        $this->assertSelectorTextContains('.alert-danger', 'Don\'t remember you already created this one?');
    }

    public function testSortCompanies(): void
    {
        $companies = $this->entityManager->getRepository(Company::class)->findAll();

        $crawler = $this->client->request('GET', '/backstage/companies');

        $form = $crawler->filter('form[name=company_sort]')->form();
        $formValues = [];
        foreach ($companies as $company) {
            $key = $company->getPosition() - 1;
            $this->assertSelectorExists("input[name='company_sort[companies][{$key}][name]']");
            $this->assertSelectorExists("input[name='company_sort[companies][{$key}][position]']");

            $this->assertSame($company->getName(), $form['company_sort[companies][' . $key . '][name]']->getValue());
            $this->assertEquals($company->getPosition(), $form['company_sort[companies][' . $key . '][position]']->getValue());

            $formValues[$key] = $company;
        }

        $newCount = 114;
        foreach ($formValues as $key => $formValue) {
            $form['company_sort[companies][' . $key . '][position]'] = $newCount;
            ++$newCount;
        }

        $this->client->submit($form);

        $this->assertResponseRedirects('/backstage/companies');
        $this->client->followRedirect();

        $this->assertSelectorExists('.alert-success');
        $this->assertSelectorTextContains('.alert-success', '404 errors feared you. Success found you.');

        $newPosition = 114;
        foreach ($companies as $company) {
            $updatedCompany = $this->entityManager->getRepository(Company::class)->find($company->getId());
            $this->assertEquals($updatedCompany->getPosition(), $newPosition);
            ++$newPosition;
        }
    }

    public function testDeleteCompany(): void
    {
        $companies = $this->entityManager->getRepository(Company::class)->findAll();
        $existingCompany = array_shift($companies);
        $id = $existingCompany->getId();

        $this->client->request('GET', "/backstage/companies/delete/{$id}");

        $this->assertResponseRedirects('/backstage/companies');
        $this->client->followRedirect();

        $this->assertSelectorExists('.alert-success');
        $this->assertSelectorTextContains('.alert-success', 'You make CRUD look good.');

        $deletedCompany = $this->entityManager->getRepository(Company::class)->find($id);
        $this->assertNull($deletedCompany);
    }
}
