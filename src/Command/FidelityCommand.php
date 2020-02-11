<?php

namespace App\Command;

use App\Service\MailerService;
use App\Repository\CardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FidelityCommand extends Command
{
    protected static $defaultName = 'app:fidelity';
    private $manager;
    private $mailer;
    private $cardRepository;

    public function __construct(MailerService $mailer, EntityManagerInterface $manager, CardRepository $cardRepository)
    {
        parent::__construct();
        $this->manager = $manager;
        $this->mailer = $mailer;
        $this->cardRepository = $cardRepository;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Suppresion des crédits expirés
        $cards = $this->cardRepository->findByDate();

        $expireCredits = 0;
        foreach ($cards as $card)
        {
            $expireCredits++;
            $card->setCredits(0);
        }
        $this->manager->flush();

        // Envoi de mail aux utilisateurs possédant des crédits arrivant à expiration dans un délais de 2 semaines
        $cards = $this->cardRepository->findExpireTwoWeeks();

        $twoWeeks = 0;
        foreach ($cards as $card)
        {
            $twoWeeks++;
            $this->mailer->sendExpireTwoWeeks($card->getUser());
        }

        $io->success('Il y a eu '.$expireCredits. ' clients qui ont eu leurs points d\'expirés.');
        $io->success('Il y a eu '.$twoWeeks. ' mails d\'envoyés pour informer les clients de l\'expiration des points.');

        return 0;
    }       
}
