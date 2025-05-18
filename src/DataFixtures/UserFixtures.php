<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const ADMIN_USER = [
        'email' => 'michael@myers.com',
        'password' => 'Pa$$woRd',
    ];

    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        // User
        $user = (new User())
            ->setEmail(self::ADMIN_USER['email'])
            ->setName('Michael Myers')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER'])
        ;

        $password = $this->passwordHasher->hashPassword($user, self::ADMIN_USER['password']);
        $user->setPassword($password);

        $manager->persist($user);

        $manager->flush();
    }
}
