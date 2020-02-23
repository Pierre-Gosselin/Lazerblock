<?php

namespace App\DataFixtures;

use App\Entity\Ticket;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class TicketFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Génération des tickets des utilisateurs
        for ($i=0; $i < 100; $i++)
        { 
            $user = rand(0,49);
            $ticket = new Ticket;
            $ticket->setUsed(rand(0,1));
            $ticket->setSerial(str_replace(' ','',$this->getReference("User".$user)->getFullname()).uniqid());
            $ticket->setUser($this->getReference("User".$user));

            $manager->persist($ticket);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
