<?php

namespace App\DataFixtures;

use App\Entity\Gift;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GiftFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $title = ['Une place gratuite', 'Une boisson', 'Un mars', 'Un twix', 'Un bounty', 'Un snickers', 'Une barbe à papa', 'Un paquet de bonbon', 'Un paquet de chips', 'Un paquet de curly', 'Un paquet de gateau', 'Un saut de popcorn'];
        $picture = ['voucher.jpg', 'boisson.jpg', 'mars.jpg', 'twix.jpg', 'bounty.jpg', 'snickers.jpg', 'barbe-a-papa.jpg', 'bonbon.jpg', 'chips.jpg', 'curly.jpg', 'gateau.jpg', 'popcorn.jpg'];
        $price = [10, 2, 3, 3, 3, 3, 2, 3, 2, 3, 4, 5];

        for ($i=0; $i < 12; $i++) { 
            $gift = new Gift;
            $gift->setTitle($title[$i])
                 ->setPicture($picture[$i])
                 ->setPrice($price[$i])
                 ->setEnabled(true);
            $this->addReference("gift".$i, $gift);

            $manager->persist($gift);
        }
        $manager->flush();
    }
}
