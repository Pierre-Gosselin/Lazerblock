<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Service\MailerService;
use App\Service\PaginationService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/ticket", name="ticket")
 */
class TicketController extends AbstractController
{
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
}
