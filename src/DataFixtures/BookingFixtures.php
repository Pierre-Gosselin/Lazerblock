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

        for($j=1; $j<=31; $j++) // Nombre de jours
        {
            for($k=10;$k<20;$k++)
            {
                $time = new \Datetime($k.":00");
                $bookingPerHour = mt_rand(4,10);

                for($l=1; $l<=$bookingPerHour; $l++) // Nombre de réservation pour une heure
                {
                    $booking = new Booking;
                    $booking->setUser($this->getReference("admin@laserwars.com"));
                    $booking->setReservationAt(new \Datetime($j." days"));
                    $booking->setTimeSlot($time);
                    $booking->setSerial(str_replace(' ','',$this->getReference("admin@laserwars.com")->getFullname()).uniqid());
        
                    $manager->persist($booking);

                }
            }
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
