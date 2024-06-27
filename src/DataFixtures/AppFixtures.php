<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\User;
use DateTime;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $now = new DateTimeImmutable();

        for ($i = 0; $i < 20; $i++) {
            $product = new Product();
            $product->setName('product ' . $i);
            $product->setPrice((string)mt_rand(10, 100));
            $product->setSku("PT-$i");
            $product->setCreatedAt($now);
            $manager->persist($product);
        }

        $user = new User();
        $user->setEmail('user@example.com');
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'secret');
        $user->setPassword($hashedPassword);
        $manager->persist($user);

        $manager->flush();
    }
}
