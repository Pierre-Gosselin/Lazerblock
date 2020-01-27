<?php

namespace App\DataFixtures;

use App\Entity\Booking;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class BookingFixtures extends Fixture implements DependentFixtureInterface
{   
    public function load(ObjectManager $manager)
    {
        for ($i=0; $i < 20; $i++) { 
            $booking = new Booking;
            $booking->setTicket($this->getReference("Ticket".$i));
            $booking->setReservationAt(new \Datetime());
            $booking->setSerial(str_replace(' ','',$this->getReference("Ticket".$i)->getUser()->getFullname()).uniqid());

            $manager->persist($booking);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            TicketFixtures::class,
        );
    }
}
