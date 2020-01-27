<?php

namespace App\Controller;

use App\Entity\Card;
use App\Form\CardType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/carte", name="card")
 */
class CardController extends AbstractController
{
    /**
     * Permet l'ajout de la carte de fidelité de l'utilisateur
     * 
     * @Route("/ajouter", name="_add")
     */
    public function index(Request $request)
    {
        $user = $this->getUser();

        if ($user->getCard())
        {
            $this->addFlash('warning', "Vous avez déjà une carte de fidélité rattacher à votre compte.");
            
            return $this->redirectToRoute('account_show');
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

            $this->addFlash('success', "Votre carte de fidélité a bien été ajoutée à votre compte, vous la trouverez dans votre profil.");
            return $this->redirectToRoute('account_show');
        }
        
        return $this->render('card/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet la création de la carte de fidélité de l'utilisateur
     * 
     * @Route("/creer", name="_create")
     *
     * @return Response
     */
    public function createCard()
    {
        $user = $this->getUser();

        if ($user->getCard())
        {
            $this->addFlash('warning', "Vous avez déjà une carte de fidélité rattacher à votre compte.");
            
            return $this->redirectToRoute('account_show');
        }

        $manager = $this->getDoctrine()->getManager();

        $card = new Card;
        $card->setUser($user);
        $card->setCredits(200);
        $card->setSerial(str_replace(' ','',$user->getFullname()).uniqid());
        $manager->persist($card);
        $manager->flush();

        $this->addFlash('success', "Votre carte de fidélité a bien été créée, Vous avez reçu 200 Credits à utiliser lors de votre prochaine visite. Vous trouverez votre carte de fidélité dans votre profil.");
        return $this->redirectToRoute('account_show');
    }
}