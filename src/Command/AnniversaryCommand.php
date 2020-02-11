<?php

namespace App\Command;

use App\Entity\Ticket;
use App\Service\MailerService;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AnniversaryCommand extends Command
{
    protected static $defaultName = 'app:anniversary';
    private $mailer;
    private $userRepository;
    private $manager;

    public function __construct(MailerService $mailer, UserRepository $userRepository, EntityManagerInterface $manager)
    {
        parent::__construct();
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
        $this->manager = $manager;
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

        $users = $this->userRepository->findAll();
        $now = new \Datetime();
        $now->setTime(23,59,59);
        $nbTicket = 0;

        foreach ($users as $user)
        {
            if (date_diff($now,$user->getCreatedAt())->format('%y') > 0)
            {
                if ($now->format('d/m') == $user->getBirthdate()->format('d/m'))
                {
                    $nbTicket++;
                    $ticket = new Ticket;
                    $serial = str_replace(' ','',$user->getFullname()).uniqid();
    
                    $ticket->setSerial($serial);
                    $ticket->setUser($user);

                    $this->manager->persist($ticket);
                    $this->manager->flush();
    
                    $this->mailer->sendAnniversary($user);
                }
            }
        }

        $io->success('Il y a eu '. $nbTicket .' place(s) d\'offerte(s) aux clients.');
        return 0;
    }
}
