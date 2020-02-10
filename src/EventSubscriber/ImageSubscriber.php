<?php

namespace App\EventSubscriber;


use App\Entity\Gift;
use App\Entity\Avatar;
use App\Service\ImageService;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ImageSubscriber implements EventSubscriberInterface
{
    private $service;

    public function __construct(ImageService $imagesService)
    {
        $this->service = $imagesService;
    }
    public static function getSubscribedEvents()
    {
        return array(
            'easy_admin.pre_persist' => array('setImage'),
            'easy_admin.pre_update' => array('setImage'),
        );
    }

    function setImage(GenericEvent $event)
    {
        $entity = $event->getSubject();

        if($entity instanceof Gift)
        {  
            $picture = $entity->getPicture();

            if ($entity->getPictureFile())
            {
                $picture = $this->service->saveToDisk($entity->getPictureFile(), '/public/images/gifts/');
            }

            $entity->setPicture($picture);
        }   
               
        if ($entity instanceof Avatar)
        {
            $picture = $entity->getPicture();

            if ($entity->getPictureFile())
            {
                $picture = $this->service->saveToDisk($entity->getPictureFile(), '/public/images/avatar/');
            }
            
            $entity->setPicture($picture);              
        }       
    }
}