<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'app:create-user', description: 'Creates a new user.')]
class CreateUserCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $user = new User();

        $output->writeln('');

        $email = $this->askEmail($input, $output);
        $name = $this->askName($input, $output);
        $isAdmin = $this->askIsAdmin($input, $output);
        $password = $this->askPassword($input, $output);

        $user->setEmail($email);
        $user->setName($name);
        if ($isAdmin) {
            $user->setRoles(['ROLE_ADMIN']);
        }
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln([
            '',
            sprintf('👤 <info>The user %s has been created successfully!</info>', $name),
            '',
        ]);

        return Command::SUCCESS;
    }

    private function askEmail(InputInterface $input, OutputInterface $output): string
    {
        $helper = $this->getHelper('question');
        $question = new Question('Email: ');

        $question->setValidator(function (string $value): string {
            if ('' === trim($value)) {
                throw new \RuntimeException('The email cannot be empty.');
            }

            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                throw new \RuntimeException('The email is not a valid email.');
            }

            if ($this->entityManager->getRepository(User::class)->findOneBy(['email' => $value])) {
                throw new \RuntimeException('The email is already used.');
            }

            return $value;
        });

        return $helper->ask($input, $output, $question); // @phpstan-ignore-line
    }

    private function askName(InputInterface $input, OutputInterface $output): string
    {
        $helper = $this->getHelper('question');
        $question = new Question('Name: ');

        $question->setValidator(function (string $value): string {
            if ('' === trim($value)) {
                throw new \RuntimeException('The name cannot be empty.');
            }

            return $value;
        });

        return $helper->ask($input, $output, $question); // @phpstan-ignore-line
    }

    private function askIsAdmin(InputInterface $input, OutputInterface $output): bool
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Is admin?', false);

        return $helper->ask($input, $output, $question); // @phpstan-ignore-line
    }

    private function askPassword(InputInterface $input, OutputInterface $output): string
    {
        $helper = $this->getHelper('question');

        $password = new Question('Password: ');
        $password->setHidden(true);
        $password->setHiddenFallback(false);

        $password->setValidator(function (string $value): string {
            if ('' === trim($value)) {
                throw new \RuntimeException('The password cannot be empty.');
            }

            return $value;
        });

        $firstPwd = $helper->ask($input, $output, $password); // @phpstan-ignore-line

        $repeatPassword = new Question('Repeat password: ');
        $repeatPassword->setHidden(true);
        $repeatPassword->setHiddenFallback(false);

        $secondPwd = $helper->ask($input, $output, $repeatPassword); // @phpstan-ignore-line

        if ($firstPwd !== $secondPwd) {
            throw new \RuntimeException('Passwords do not match.');
        }

        return $firstPwd;
    }
}
