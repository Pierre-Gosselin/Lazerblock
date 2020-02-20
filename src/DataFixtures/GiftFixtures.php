<?php

namespace App\DataFixtures;

use App\Entity\Gift;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GiftFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Ajout des friandises
        $title = ['Une boisson', 'Un mars', 'Un twix', 'Un bounty', 'Un snickers', 'Une barbe à papa', 'Un paquet de bonbon', 'Un paquet de chips', 'Un paquet de curly', 'Un paquet de gateau', 'Un saut de popcorn'];
        $picture = ['boisson.jpg', 'mars.jpg', 'twix.jpg', 'bounty.jpg', 'snickers.jpg', 'barbe-a-papa.jpg', 'bonbon.jpg', 'chips.jpg', 'curly.jpg', 'gateau.jpg', 'popcorn.jpg'];
        $price = [400, 400, 400, 400, 400, 400, 500, 500, 500, 500, 500];

        for ($i=0; $i < count($title); $i++)
        { 
            $gift = new Gift;
            $gift->setTitle($title[$i])
                 ->setPicture($picture[$i])
                 ->setPrice($price[$i])
                 ->setCategory('Friandises')
                 ->setEnabled(true);
            $this->addReference("gift".$i, $gift);

            $manager->persist($gift);
        }

        // Ajout des costumes
        $title = [];
        $picture = [];
        $price = [];

        for ($i=0; $i < count($title); $i++)
        { 
            
            $manager->persist($gift);
            
        }
        $manager->flush();
    }
}
