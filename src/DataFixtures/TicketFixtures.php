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
        for ($i=0; $i < 20; $i++) { 
            $ticket = new Ticket;
            $ticket->setSerial(str_replace(' ','',$this->getReference("admin@laserwars.com")->getFullname()).uniqid());
            $ticket->setUser($this->getReference("admin@laserwars.com"));
            $this->setReference("Ticket".$i, $ticket);

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
