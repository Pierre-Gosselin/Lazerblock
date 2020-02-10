<?php

namespace App\Controller;

use DateTime;
use App\Entity\Booking;
use App\Entity\Ticket;
use App\Service\BookingService;
use App\Service\MailerService;
use App\Service\PaginationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/reservation", name="booking")
 */
class BookingController extends AbstractController
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Permet d'afficher les réservations de l'utilisateur
     * 
     * @Route("s/{page<\d+>?1}", name="_show")
     * 
     * @return Response
     */
    public function index($page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Booking::class)
                   ->setCurrentPage($page)
                   ->setUser($this->getUser());

        return $this->render('booking/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * Permet d'afficher le calendrier de réservation et de choisir sa date, son créneau, et le nombre de personne.
     * L'utilisateur ne pourra pas faire plusieurs réservation ne même temps.
     * 
     * @Route("/", name="_create")
     * 
     * @return Response
     */
    public function booking(BookingService $bookingService)
    {
        if ($bookingService->userBooking())
        {
            $this->addFlash('warning', "Vous ne pouvez pas avoir plusieurs réservations simultanément.");
            return $this->redirectToRoute('booking_show');
        }

        $notAvailable = $bookingService->getNotAvailable();

        return $this->render('booking/create.html.twig', [
            'notAvailable' => $notAvailable,
        ]);
    }

    /**
     * Permet d'afficher la page de confirmation de sa réservation
     * 
     * @Route("/confirmation", name="_confirm")
     *
     * @param Request $request
     * @return void
     */
    public function confirm(Request $request, BookingService $bookingService)
    {
        if ($bookingService->userBooking())
        {
            $this->addFlash('warning', "Vous ne pouvez pas avoir plusieurs réservations simultanément.");
            return $this->redirectToRoute('booking_show');
        }

        if ($request->isMethod('POST'))
        {
            $reservedAt = $request->request->get('hiddenDate');
            $time =  $request->request->get('hiddenTime');
            $bookings = $request->request->get('bookings');

            $ticketsToUse = $this->getDoctrine()->getRepository(Ticket::class)->findBy(['user' => $this->getUser(),'used' => 0]);

            // On s'assure que la date soit bien disponible
            if ($bookingService->notReservableDate(DateTime::createFromFormat('d/m/Y',$reservedAt)))
            {
                $this->addFlash('warning', "Cette date n'est pas disponible, veuillez en choisir une autre.");
                return $this->redirectToRoute('booking_create');
            }

            // On s'assure que le créneau horaire soit disponible en fonction du nombre de personnes
            if ($bookingService->notReservableTime(DateTime::createFromFormat('d/m/Y',$reservedAt), DateTime::createFromFormat('H:i',$time), $bookings))
            {
                $this->addFlash('warning', "Ce créneau horaire n'est pas disponible, veuillez en choisir un autre.");
                return $this->redirectToRoute('booking_create');
            }

            // On enregistre les données en session
            $this->session->set('bookings', $bookings);
            $this->session->set('reservedAt', $reservedAt);
            $this->session->set('time', $time);
            $this->session->set('path', "bookings");
            
            return $this->render('booking/confirm.html.twig', [
                'bookings' => $bookings,
                'reservedAt' => $bookingService->dateToFr(DateTime::createFromFormat('d/m/Y',$reservedAt)),
                'time' => $time,
                'ticketsToUse' => $ticketsToUse,
            ]);
        }
        return $this->redirectToRoute('booking_create');
    }

    /**
     * Permet d'utiliser ses tickets pour faire la réservation
     *
     * @Route("/ticket", name="_use_ticket")
     * 
     * @return void
     */
    public function useTicket(BookingService $bookingService)
    {
        if ($bookingService->userBooking())
        {
            $this->addFlash('warning', "Vous ne pouvez pas avoir plusieurs réservations simultanément.");
            return $this->redirectToRoute('booking_show');
        }

        // On récupère le nombre de tickets à utiliser
        $bookings = $this->session->get('bookings');

        if ($bookings)
        {
            // On récupère les tickets utilisables
            $ticketsToUse = $this->getDoctrine()->getRepository(Ticket::class)->findBy(['user' => $this->getUser(),'used' => 0],[],$bookings,0);

            if ($bookings <= count($ticketsToUse))
            {
                $manager = $this->getDoctrine()->getManager();
                foreach ($ticketsToUse as $ticket)
                {
                    $ticket->setUsed(true);
                    $manager->flush($ticket);
                }
            }
            else
            {
                return $this->redirectToRoute('booking_show');
            }   

            return $this->redirectToRoute('booking_save');
        }

        return $this->redirectToRoute('booking_show');
    }

    /**
     * Permet d'enregistrer la ou les réservation
     * 
     * @Route("/enregistrement", name="_save")
     *
     * @param Request $request
     * @return void
     */
    public function save(MailerService $mailerService, BookingService $bookingService)
    {
        if ($bookingService->userBooking())
        {
            $this->addFlash('warning', "Vous ne pouvez pas avoir plusieurs réservations simultanément.");
            return $this->redirectToRoute('booking_show');
        }

        $reservedAt = DateTime::createFromFormat('d/m/Y', $this->session->get('reservedAt'));
        $time = DateTime::createFromFormat('H:i', $this->session->get('time'));
        $bookings = $this->session->get('bookings');

        if ($bookings)
        {
            for ($i=0;$i<$bookings;$i++)
            {
                $booking = new Booking;
                $booking->setReservationAt($reservedAt);
                $booking->setTimeSlot($time);
                $booking->setSerial(str_replace(' ','',$this->getUser()->getFullname()).uniqid());
                $booking->setUser($this->getUser());
    
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($booking);
                $manager->flush();
            }
    
            $mailerService->sendBooking($this->getUser(), $bookings, $reservedAt ,$this->session->get('time'));
                
            $this->session->remove('bookings');
            $this->session->remove('reservedAt');
            $this->session->remove('time');
            $this->session->remove('path');

            $this->addFlash('success', "La réservation a bien été réalisée, vous avez reçu un email de confirmation.");
        }
        return $this->redirectToRoute('booking_show');
    }

    /**
     * Permet de retourner les créneaux disponible
     * 
     * @Route("/check", name="_check", methods={"POST"})
     *
     * @return void
     */
    public function check(Request $request, BookingService $bookingService)
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        'XMLHttpRequest' === $_SERVER['HTTP_X_REQUESTED_WITH'])
        {
            if ($request->isMethod('POST'))
            {
                $bookingAt = $request->request->get('bookingAt');

                $timeSlot = $bookingService->getSlotAvailable($bookingAt);

                return new Response($timeSlot) ;
            }
        }
    }
}
