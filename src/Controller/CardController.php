<?php

namespace App\Controller;

use App\Entity\Card;
use App\Form\CardType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CardController extends AbstractController
{
    /**
     * @Route("/addcard", name="add_card")
     */
    public function index(Request $request)
    {
        $user = $this->getUser();

        if ($user->getCard())
        {
            $this->addFlash('warning', "Vous avez déjà une carte de fidélité rattacher à votre compte.");
            return $this->redirectToRoute('home');
        }

        $card = new Card;

        $form = $this->createForm(CardType::class, $card);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager = $this->getDoctrine()->getManager();
            $card->setUser($user);
            $manager->persist($card);
            $user->setCard($card);
            $manager->flush();

            $this->addFlash('success', "Votre carte de fidélité a bien été ajoutée.");
            return $this->redirectToRoute('home');
        }
        
        return $this->render('card/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
