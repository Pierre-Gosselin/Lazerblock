<?php
namespace App\Service;

use App\Entity\Ticket;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use App\Entity\User;

class MailerService{
    private $urlGenerator;
    private $mailer;

    public function __construct( UrlGeneratorInterface $urlGenerator, MailerInterface $mailer )
    {
        $this->urlGenerator = $urlGenerator;
        $this->mailer = $mailer;
    }

    /**
     * Permet l'envoi d'un mail d'activation
     *
     * @param User $user
     * @return void
     */
    public function sendActivationMail( User $user )
    {
        $url = $this->urlGenerator->generate( 'activate', array(
            'token' => $user->getToken(),
        ), UrlGenerator::ABSOLUTE_URL);

        $text = 'Bonjour, veuillez activer votre compte : ' . $url;

        $this->send( $user->getEmail(), "Activation de compte", $text );
    }

    /**
     * Permet l'envoi d'un mail de réinitialisation de mot de passe
     *
     * @param User $user
     * @return void
     */
    public function sendResetPassword( User $user)
    {
        $url = $this->urlGenerator->generate('reset_password', array(
            'token' => $user->getToken(),
        ), UrlGenerator::ABSOLUTE_URL);

        $text = "Bienvenue sur Lazer Wars !!!,
        Pour réinitialiser votre mot de passe, veuillez cliquer sur le lien ci dessous
        ou copier/coller dans votre navigateur internet.
        ". $url ."
        ---------------
        Ceci est un mail automatique, Merci de ne pas y répondre.";
        
        $this->send( $user->getEmail(), "Renouvellement de mot de passe", $text);
    }

    /**
     * Permet l'envoi de mail
     *
     * @param string $email
     * @param string $subject
     * @param string $text
     * @return void
     */
    private function send(string $email, string $subject, string $text ){
        $message = new Email();
        $message->from( 'no-reply@lazerwars.com' );
        $message->to( $email );
        $message->subject($subject);
        $message->text( $text );

        $this->mailer->send( $message );
    }


    public function giftgenerate($email, $gift)
    {
        $text = 'Bravo tu as reçu '.$gift.' à utiliser dans votre laser wars !!!';
        
        //send mail
        $this->send( $email, "un nouveau cadeau pour vous.", $text);
    }

    public function offerTicket($email, Ticket $ticket)
    {
        $text = "Votre amis vous a envoyé un ticket à utiliser dans nos locaux ".$ticket->getSerial() ;
        
        $this->send($email, "Un nouveau ticket de votre amis.", $text);
    }
}