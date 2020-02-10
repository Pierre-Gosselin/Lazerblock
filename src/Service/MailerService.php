<?php
namespace App\Service;

use App\Entity\User;
use App\Entity\Ticket;
use App\Service\BookingService;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MailerService
{
    private $urlGenerator;
    private $mailer;

    public function __construct(UrlGeneratorInterface $urlGenerator, MailerInterface $mailer)
    {
        $this->urlGenerator = $urlGenerator;
        $this->mailer = $mailer;
    }

    /**
     * Permet l'envoi de mail
     *
     * @param string $email
     * @param string $subject
     * @param string $text
     * @return void
     */
    private function send(string $adresse, string $subject, $template, $context)
    {
        $email = (new TemplatedEmail())
            ->from('laserwars@gosselin.info')
            ->to(new Address($adresse))
            ->subject("Laser Wars - ".$subject)
            ->htmlTemplate("emails/".$template)
            ->context($context)
        ;

        $this->mailer->send($email);
    }

    /**
     * Permet l'envoi d'un mail d'activation
     *
     * @param User $user
     * @return void
     */
    public function sendActivationMail(User $user)
    {
        $url = $this->urlGenerator->generate( 'activate', array(
            'token' => $user->getToken(),
        ), UrlGenerator::ABSOLUTE_URL);

        $this->send($user->getEmail(), "Activation de compte", "activate.html.twig", [
            'user' => $user,
            'url' => $url,
        ]);
    }

    /**
     * Permet l'envoi d'un mail de réinitialisation de mot de passe
     *
     * @param User $user
     * @return void
     */
    public function sendResetPassword(User $user)
    {
        $url = $this->urlGenerator->generate('reset_password', array(
            'token' => $user->getToken(),
        ), UrlGenerator::ABSOLUTE_URL);
        
        $this->send($user->getEmail(), "Renouvellement de mot de passe", "resetPassword.html.twig", [
            'user' => $user,
            'url' => $url,
        ]);
    }

    public function giftgenerate(User $user, $giftSerial, $giftTitle)
    {
        $this->send($user->getEmail(), "Un nouveau cadeau pour vous.", "giftGenerate.html.twig", [
            'user' => $user,
            'serial' => $giftSerial,
            'title' => $giftTitle,
        ]);
    }

    public function offerTicket(User $user, $email, Ticket $ticket)
    {
        $this->send($email, "Un nouveau ticket de votre amis.", "offerTicket.html.twig", [
            'user' => $user,
            'serial' => $ticket->getSerial(),
        ]);
    }

    public function sendBooking(User $user, $bookings, $date, $timeSlot)
    {
        $bookingService = new BookingService;

        $this->send($user->getEmail(), "Confirmation de réservation.", "booking.html.twig", [
            'user' => $user,
            'date' => $bookingService->dateToFr($date),
            'timeSlot' => $timeSlot,
            'bookings' => $bookings,
        ]);
    }

    public function sendConfirmBuy(User $user, $sum)
    {
        $this->send($user->getEmail(), "Confirmation de paiement", "confirm_payment.html.twig", [
            'user' => $user,
            'sum' => $sum,
        ]);
    }
}