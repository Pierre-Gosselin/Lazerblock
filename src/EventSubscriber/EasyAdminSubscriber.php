<?php

namespace App\EventSubscriber;

use App\Entity\Card;
use App\Entity\Ticket;
use App\Entity\Booking;
use App\Entity\CardGift;
use App\Service\ImageService;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $service;

    public function __construct(ImageService $imagesService)
    {
        $this->service = $imagesService;
    }
    public static function getSubscribedEvents()
    {
        return array(
            'easy_admin.pre_persist' => array('setAutomatiqueSerialSlug'),
        );
    }

    function setAutomatiqueSerialSlug(GenericEvent $event)
    {
        $entity = $event->getSubject();

        if ($entity instanceof Card || $entity instanceof Ticket || $entity instanceof Booking)
        {
            $serial = "";
            if ($entity->getUser())
            {
                $user = $entity->getUser();
                $serial = str_replace(' ','',$user->getFullname());
            }

            $entity->setSerial($serial.uniqid());
            $event['entity'] = $entity;
        }
        
        if($entity instanceof CardGift)
        {
            $user = $entity->getCards()->getUser();       
            $entity->setSerial(str_replace(' ','',$user->getFullname()).uniqid());
            $event['entity'] = $entity;
        }   
    }
}    