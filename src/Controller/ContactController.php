<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Service\MailerService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, MailerService $mailerService)
    {
        $contact = new Contact;
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $email = $contact->getEmail();

            if (filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($contact);
                $manager->flush();
                
                $sujet = $contact->getSujet();
                $message = $contact->getMessage();
                $mailerService->contact($email, $sujet, $message);
    
                $this->addFlash('info', 'Nous avons bien reçu votre email, vous recevrez une réponse dans les plus brefs délais.');
                return $this->redirectToRoute('home');
            }
            $this->addFlash('warning', "L'adresse email saisie n'est pas valide, veuillez en saisir une autre.");
            return $this->redirectToRoute('contact');    
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
            'userEmail' => $this->getUser()? $this->getUser()->getEmail() : "",
        ]);
    }

}
