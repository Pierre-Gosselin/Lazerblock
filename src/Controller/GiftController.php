<?php

namespace App\Controller;

use App\Entity\CardGift;
use App\Entity\Gift;
use App\Repository\GiftRepository;
use App\Service\MailerService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GiftController extends AbstractController
{
    /**
     * 
     * @Route("/boutique", name="boutique")
     */
    public function index(GiftRepository $giftRepository)
    {
        $gifts = $giftRepository->findAll();

        return $this->render('gift/index.html.twig', [
            'gifts' => $gifts,
        ]);
    }

    /**
     * 
     * @Route("/buy/{id}", name="buy")
     */
    public function buy(Gift $gift, MailerService $mailer)
    {
        if ($this->getUser())
        {
            $user = $this->getUser();
            $card = $user->getCard();

            // Si le cadeau est disponnible
            if ($gift->getEnabled())
            {
                // On vérifie que l'utilisateur dispose bien des crédits suffisants
                if ($card->getCredits() >= $gift->getPrice())
                {
                    $manager = $this->getDoctrine()->getManager();
    
                    $card->setCredits($card->getCredits() - $gift->getPrice());
    
                    $cardgift = new CardGift;
                    $cardgift->setSerial(str_replace(' ','',$user->getFullname()).uniqid());
                    $cardgift->setCards($card);
                    $cardgift->setGifts($gift);
                    $manager->persist($cardgift);
                    
                    $manager->flush();
    
                    $this->addFlash('success', 'Vous avez bien reçu votre cadeau, un mail de confirmation vous a été envoyé.');
    
                    $mailer->giftgenerate($user->getEmail(), $gift->getTitle());
                }
                else
                {
                    $this->addFlash('danger', 'Attention, vos crédits ne sont pas suffisant !');
                }
            }
            else
            {
                $this->addFlash('warning', 'Ce cadeau n\'est pas disponnible, veuillez en choisir un autre.');
            }

            return $this->redirectToRoute('boutique');
        }
        return $this->redirectToRoute('home');
    }
}
