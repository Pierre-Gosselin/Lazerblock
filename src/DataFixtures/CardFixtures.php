<?php

namespace App\DataFixtures;

use App\Entity\Card;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class CardFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Carte de fidélité des utilisateures
        for ($i=0; $i < 50; $i++) { 
            $card = new Card;
            $card->setUser($this->getReference("User".$i))
                 ->setCredits(rand(0,19)*100)
                 ->setSerial(str_replace(' ','',$this->getReference("User".$i)->getFullname()).uniqid());

            $this->addReference("cardUser".$i, $card);

            $manager->persist($card);
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
