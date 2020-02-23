<?php

namespace App\DataFixtures;

use App\Entity\User;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->encoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        // Fixture Admin
        $admin = new User;
        $admin
            ->setEmail("admin@laserwars.com")
            ->setPassword($this->encoder->encodePassword($admin, "12345678"))
            ->setFirstName("Monsieur")
            ->setLastName("Admin")
            ->setEnabled(1)
            ->setSide('Jedi')
            ->setRoles(["ROLE_ADMIN"])
            ->setBirthdate(new \Datetime('now'))
            ->setNewsletter(true);

        $this->addReference("admin@laserwars.com" , $admin);

        $manager->persist($admin);

        // Fixture Hôte de caisse
        $cashier = new User;
        $cashier
            ->setEmail("hote@laserwars.com")
            ->setPassword($this->encoder->encodePassword($cashier, "12345678"))
            ->setFirstName("Madame")
            ->setLastName("Hote")
            ->setEnabled(1)
            ->setSide('Jedi')
            ->setRoles(["ROLE_CASHIER"])
            ->setBirthdate(new \Datetime('now'))
            ->setNewsletter(true);

        $this->addReference("hote@laserwars.com" , $cashier);

        $manager->persist($cashier);


        // Fixtures utilisateurs
        $faker = Factory::create('fr_FR');
        $side = ['Jedi', 'Sith'];
        for ($i=0; $i < 50; $i++)
        { 
            $user = new User;
            $user
                ->setEmail($faker->email())
                ->setPassword($this->encoder->encodePassword($user, "12345678"))
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setEnabled(rand(0,1))
                ->setSide($side[rand(0,1)])
                ->setBirthdate(new \Datetime('now'))
                ->setNewsletter(true)
                ->setAvatar($this->getReference("Avatar".rand(0,9)));

            $this->addReference("User".$i , $user);    

            $manager->persist($user);
        }

        // Fixtures utilisateur Jedi
        for ($i=0; $i < 3; $i++)
        { 
            $user = new User;
            $user
                ->setEmail($faker->email())
                ->setPassword($this->encoder->encodePassword($user, "12345678"))
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setEnabled(1)
                ->setSide("Jedi")
                ->setBirthdate(new \Datetime('now'))
                ->setNewsletter(true)
                ->setAvatar($this->getReference("Avatar".rand(0,9)));

            $this->addReference("Jedi".$i , $user);    

            $manager->persist($user);
        }

        // Fixtures utilisateur Sith
        for ($i=0; $i < 3; $i++)
        { 
            $user = new User;
            $user
                ->setEmail($faker->email())
                ->setPassword($this->encoder->encodePassword($user, "12345678"))
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setEnabled(1)
                ->setSide("Sith")
                ->setBirthdate(new \Datetime('now'))
                ->setNewsletter(true)
                ->setAvatar($this->getReference("Avatar".rand(0,9)));
                
            $this->addReference("Sith".$i , $user);    

            $manager->persist($user);
        }
    
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            AvatarFixtures::class,
        );
    }
}
