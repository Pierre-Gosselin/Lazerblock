<?php

namespace App\DataFixtures;

use App\Entity\CardGift;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class CardGiftFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Ajout de cadeaux pour l'admin
        for($i=0; $i<50; $i++)
        {
            $cardGift = new CardGift;
            $cardGift->setCards($this->getReference("card".$this->getReference("admin@laserwars.com")->getId()));
            $cardGift->setGifts($this->getReference("gift".rand(0,11)));
            $cardGift->setExpiredAt(new \Datetime());
            $cardGift->setSerial(str_replace(' ','',$this->getReference("admin@laserwars.com")->getFullname()).uniqid());
            
            $manager->persist($cardGift);
        }

        // Ajout de cadeaux pour les utilisateurs

        for($i=0; $i<100; $i++)
        {
            $randUser = rand(0,49);

            $cardGift = new CardGift;
            $cardGift->setCards($this->getReference("cardUser".$randUser));
            $cardGift->setGifts($this->getReference("gift".rand(0,11)));
            $cardGift->setExpiredAt(new \Datetime());
            $cardGift->setSerial(str_replace(' ','',$this->getReference("User".$randUser)->getFullname()).uniqid());
            
            $manager->persist($cardGift);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            CardFixtures::class,
            GiftFixtures::class,
        );
    }
}
