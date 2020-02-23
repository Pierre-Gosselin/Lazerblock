<?php

namespace App\DataFixtures;

use App\Entity\Avatar;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AvatarFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $picture = ['avatar17.png', 'avatar18.png', 'avatar19.png', 'avatar12.png', 'avatar13.png', 'avatar15.png', 'avatar16.png', 'avatar20.png', 'avatar21.png', 'avatar26.png', ];
        $title = ['R2-D2','BB8','Chewbacca','Yoda','Padme','Boba Fett','Dark Vador','Kylo Ren','Stromtrooper','Dark Maul'];

        for ($i=0; $i < 10 ; $i++) { 
            $avatar = new Avatar;
            $avatar->setTitle($title[$i]);
            $avatar->setPicture($picture[$i]);

            $this->addReference("Avatar".$i , $avatar); 
            
            $manager->persist($avatar);
        }

        $manager->flush();
    }
}
