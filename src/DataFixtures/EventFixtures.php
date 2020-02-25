<?php

namespace App\DataFixtures;

use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class EventFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $title = ['l\'anniversaire de la première sortie de Star Wars', 'l\'anniversaire de l\'ouverture du Laser Wars'];
        $startDate = [new \Datetime('2020-10-17'), new \Datetime('2020-02-17')];
        $endDate = [new \Datetime('2020-10-18'), new \Datetime('2020-03-02')];
        $multiplicator = [2,1.5];
        $description = ["une place acheté, une place offerte", "deux places achetées, une place offerte"];

        for ($i=0; $i < count($title); $i++) { 
            $event = new Event;
            $event->setTitle($title[$i])
                 ->setStartEvent($startDate[$i])
                 ->setEndEvent($endDate[$i])
                 ->setMultiplicator($multiplicator[$i])
                 ->setDescription($description[$i]);

            $manager->persist($event);
        }
        $manager->flush();
    }
}
