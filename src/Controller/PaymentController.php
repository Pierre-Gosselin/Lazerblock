<?php

namespace App\Controller;

use Stripe\Charge;
use Stripe\Stripe;
use App\Entity\Card;
use App\Service\MailerService;
use App\Service\BookingService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/paiement", name="payment")
 */
class PaymentController extends AbstractController
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
    
    /**
     * @Route("", name="_show")
     */
    public function index(BookingService $bookingService)
    {
        $path = $this->session->get('path');

        if ($path == "bookings")
        {
            if ($bookingService->userBooking())
            {
                $this->addFlash('warning', "Vous ne pouvez pas avoir plusieurs réservations simultanément.");
                return $this->redirectToRoute('booking_show');
            }
        }
        
        $places = $this->session->get($path);

        $sum = (15 - ( $places - 1)) * $places;

        return $this->render('payment/index.html.twig', [
            'sum' => $sum,
            'places' => $places,
        ]);
    }

    /**
     * @Route("/verification", name="_charge")
     *
     * @param Request $request
     */
    public function charge(Request $request, MailerService $mailerService, BookingService $bookingService)
    {
        $path = $this->session->get('path');

        if ($path == "bookings")
        {
            if ($bookingService->userBooking())
            {
                $this->addFlash('warning', "Vous ne pouvez pas avoir plusieurs réservations simultanément.");
                return $this->redirectToRoute('booking_show');
            }
        }

        $places = $this->session->get($path);

        $sum = (15 - ( $places - 1)) * $places;

        \Stripe\Stripe::setApiKey("sk_test_5eSQvPS69esSB87KyoKYOKa600c7ZyBuLG");

        try
        {
            \Stripe\Charge::create([
                'amount' => $sum*100,
                'currency' => 'eur',
                'description' => 'Paiement de '.$places." places par ".$this->getUser()->getFullname(),
                'source' => $request->request->get('stripeToken'),
            ]);
        } catch (\Exception $e)
        {
            $this->addFlash('warning', "Le paiement a été refusé.");
            return $this->redirectToRoute('payment_show');
        }
        
        $card = $this->getDoctrine()->getRepository(Card::class)->findOneByUser($this->getUser());
        
        $manager = $this->getDoctrine()->getManager();

        if ($card)
        {
            $card->setCredits($card->getCredits() + $places *100);
        }
        else
        {
            $card = new Card;
            $card->setUser($this->getUser());
            $card->setCredits(200 + $places *100);
            $card->setSerial(str_replace(' ','',$this->getUser()->getFullname()).uniqid());
            $manager->persist($card);
        }
        $manager->flush();
        
        $this->addFlash('success', "Le paiement a bien été effectué, vous avez reçu un email de confirmation.");
        $mailerService->sendConfirmBuy($this->getUser(), $sum);

        if ($path == "tickets") return $this->redirectToRoute('ticket_confirm');

        if ($path == "bookings") return $this->redirectToRoute('booking_save');
    }
}
