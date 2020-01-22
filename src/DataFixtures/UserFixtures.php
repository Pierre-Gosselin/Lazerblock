<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->encoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        $admin = new User;
        $admin
            ->setEmail("admin@lazerwars.com")
            ->setPassword($this->encoder->encodePassword($admin, "12345678"))
            ->setFirstName("David")
            ->setLastName("Hasselhoff")
            ->setEnabled(1)
            ->setSide(1)
            ->setRoles(["ROLE_ADMIN"])
            ->setBirthdate(new \Datetime('now'))
            ->setNewsletter(true);

        $cashier = new User;
        $cashier
            ->setEmail("jeanine@lazerwars.com")
            ->setPassword($this->encoder->encodePassword($cashier, "12345678"))
            ->setFirstName("Jeanine")
            ->setLastName("Duval")
            ->setEnabled(1)
            ->setSide(1)
            ->setRoles(["ROLE_CASHIER"])
            ->setBirthdate(new \Datetime('now'))
            ->setNewsletter(true);

    $manager->persist($admin);
    $manager->persist($cashier);
    $manager->flush();
    }
}
