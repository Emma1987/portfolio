<?php

namespace App\Tests\Command;

use App\Command\CreateUserCommand;
use App\Entity\User;
use App\Tests\AdminWebTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserCommandTest extends AdminWebTestCase
{
    private ?CommandTester $commandTester = null;

    protected function setUp(): void
    {
        parent::setUp();

        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $passwordHasher->method('hashPassword')->willReturn('hashed_password');

        $command = new CreateUserCommand($this->entityManager, $passwordHasher);

        $application = new Application();
        $application->add($command);
        $application->setAutoExit(false);

        $this->commandTester = new CommandTester($application->find('app:create-user'));
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->commandTester = null;
    }

    public function testExecuteCreatesUserSuccessfully(): void
    {
        $this->commandTester->setInputs([
            'hannibal@lecter.com',  // Email
            'Hannibal',             // Name
            'no',                   // Is admin
            'Pa$$woRd1234',         // Password
            'Pa$$woRd1234',         // Repeat password
        ]);
        $this->commandTester->execute([]);

        $this->commandTester->assertCommandIsSuccessful();

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('The user Hannibal has been created successfully!', $output);

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'hannibal@lecter.com']);
        $this->assertNotNull($user);
        $this->assertEquals('Hannibal', $user->getName());
        $this->assertFalse(in_array('ROLE_ADMIN', $user->getRoles()));
    }

    public function testInvalidEmailThrowsException(): void
    {
        $this->expectException(RuntimeException::class);

        $this->commandTester->setInputs([
            'invalid-email',    // Invalid email
            'Hannibal',         // Name
            'no',               // Is admin
            'Pa$$woRd1234',     // Password
            'Pa$$woRd1234',     // Repeat password
        ]);
        $this->commandTester->execute([]);
    }

    public function testAlreadyExistingEmailThrowsException(): void
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();
        $user = array_shift($users);

        $this->expectException(RuntimeException::class);

        $this->commandTester->setInputs([
            $user->getEmail(),  // Existing email
            'Hannibal',         // Name
            'no',               // Is admin
            'Pa$$woRd1234',     // Password
            'Pa$$woRd1234',     // Repeat password
        ]);
        $this->commandTester->execute([]);
    }

    public function testPasswordMismatchThrowsException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Passwords do not match.');

        $this->commandTester->setInputs([
            'hannibal@lecter.com',  // Email
            'Hannibal',             // Name
            'no',                   // Is admin
            'Pa$$woRd1234',         // Password
            'differentPassword',    // Repeat password
        ]);
        $this->commandTester->execute([]);
    }
}
