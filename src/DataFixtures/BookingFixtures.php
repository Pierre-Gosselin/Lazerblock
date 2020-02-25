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
        // Fixtures réservation des utilisateurs
        for($j=1; $j<=31; $j++) // 31 jours
        {
            for($k=10; $k<20; $k++) // 10 créneaux horraires
            {
                $time = new \Datetime($k.":00");
                $bookingPerHour = mt_rand(4,10);

                for($l=1; $l<=$bookingPerHour; $l++)
                {
                    $booking = new Booking;
                    $user = "User".rand(0,49);
                    $booking->setUser($this->getReference($user));
                    $booking->setReservationAt(new \Datetime($j." days"));
                    $booking->setTimeSlot($time);
                    $booking->setSerial(str_replace(' ','',$this->getReference($user)->getFullname()).uniqid());
        
                    $manager->persist($booking);
                }
            }
        }

        // Fixtures réservation passée
        $booking = new Booking;
        $booking->setUser($this->getReference("UserBookings"));
        $booking->setReservationAt(new \Datetime("-3 days"));
        $booking->setTimeSlot(new \Datetime('10:00'));
        $booking->setPseudo("Maitre Yoda");
        $booking->setScore("900");
        $booking->setSerial(str_replace(' ','',$this->getReference("UserBookings")->getFullname()).uniqid());

        $manager->persist($booking);
        
        // Fixtures score Jedi
        $jediScore = [1600, 1800, 1200];
        $jediPseudo = ["Annakin", "Maitre Yoda", "Rey"];
        for($i=0; $i<3; $i++)
        {
            $booking = new Booking;
            $booking->setUser($this->getReference("Jedi".$i));
            $booking->setReservationAt(new \Datetime("-3 days"));
            $booking->setTimeSlot($time);
            $booking->setSerial(str_replace(' ','',$this->getReference("Jedi".$i)->getFullname()).uniqid());
            $booking->setScore($jediScore[$i]);
            $booking->setPseudo($jediPseudo[$i]);

            $manager->persist($booking);
        }

        // Fixtures score Sith
        $sithScore = [1900, 1300, 1500];
        $sithPseudo = ["Dark Maul", "Dark Vador", "Palpatine"];
        for($i=0; $i<3; $i++)
        {
            $booking = new Booking;
            $booking->setUser($this->getReference("Sith".$i));
            $booking->setReservationAt(new \Datetime("-3 days"));
            $booking->setTimeSlot($time);
            $booking->setSerial(str_replace(' ','',$this->getReference("Sith".$i)->getFullname()).uniqid());
            $booking->setScore($sithScore[$i]);
            $booking->setPseudo($sithPseudo[$i]);

            $manager->persist($booking);
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
