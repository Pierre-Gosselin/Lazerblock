<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Service\MailerService;
use App\Service\PaginationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/ticket", name="ticket")
 */
class TicketController extends AbstractController
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }   

    /**
     * Permet d'afficher la liste des tickets de l'utilisateur
     * 
     * @Route("/{page<\d+>?1}", name="_show")
     * 
     * @return Response
     * 
     */
    public function index($page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Ticket::class)
                   ->setCurrentPage($page)
                   ->setUser($this->getUser());

        return $this->render('ticket/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * Permet de donner un ticket à un amis
     * 
     * @Route("/offrir/{serial}", name="_give")
     *
     * @return Response
     */
    public function give(Ticket $ticket, Request $request, MailerService $mailerService)
    {
        if ($ticket->getUsed())
        {
            $this->addFlash('warning', "Le ticket a déjà été utilisé, veuillez en choisir un autre.");
            return $this->redirectToRoute('ticket_show');    
        }

        if ($ticket->getUser() == $this->getUser())
        {
            if ($request->isMethod('POST'))
            {           
                $email = $request->request->get('email');
    
                if (filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                    $mailerService->offerTicket($this->getUser(), $email, $ticket);
    
                    $this->addFlash('info', 'Un mail a été envoyé à votre ami avec le ticket offert.');
                    return $this->redirectToRoute('ticket_show');
                }
                $this->addFlash('warning', "L'adresse email saisie n'est pas valide, veuillez en saisir une autre.");     
            }
        }
        else
        {
            $this->addFlash('danger', "Numéro de ticket inconnu");
            return $this->redirectToRoute('ticket_show');
        }
        return $this->render('ticket/offer.html.twig');
    }

    /**
     * @Route("/acheter", name="_buy")
     *
     * @param Request $request
     * @return void
     */
    public function buy(Request $request)
    {
        if ($request->isMethod('POST'))
        {
            $tickets = $request->request->get('tickets');
        
            if ($tickets > 0 && $tickets < 6)
            {
                $this->session->set('tickets', $tickets);
                $this->session->set('path', "tickets");
    
                return $this->redirectToRoute('payment_show');
            }
            $this->addFlash('warning', "Vous pouvez acheter entre 1 et 5 tickets maximun");
        }
        
        return $this->render('ticket/buy.html.twig');
    }

    /**
     * @Route("/confirmation", name="_confirm")
     *
     * @return void
     */
    public function confirm()
    {
        $tickets = $this->session->get('tickets');
        
        if ($tickets)
        {
            for ($i=0; $i < $tickets ; $i++)
            { 
                $manager = $this->getDoctrine()->getManager();
                $ticket = new Ticket;
                $ticket->setSerial(str_replace(' ','',$this->getUser()->getFullname()).uniqid());
                $ticket->setUser($this->getUser());
    
                $manager->persist($ticket);
            }
            //$this->session->remove('tickets');
            $this->session->remove('path');
    
            $manager->flush();
    
            return $this->render('ticket/confirm.html.twig', [
                'tickets' => $tickets,
                'user' => $this->getUser(),
            ]);
        }
        return $this->redirectToRoute('ticket_show');
    }
}
