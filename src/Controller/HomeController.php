<?php

namespace App\Controller;

use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(BookingRepository $bookingRepository)
    {

        $jedi_score =  $bookingRepository->findByScore("Jedi");
        $sith_score = $bookingRepository->findByScore("Sith");

        return $this->render('home/index.html.twig',[
            'jedi_scores'=> $jedi_score,
            'sith_scores'=> $sith_score,
        ]);
    }


    /**
     * @Route("/mentionlegal", name="mentionlegal")
     *
     */
    public function mentionlegal()
    {
        return $this->render('home/mentionlegal.html.twig');
    }


    /**
     * @Route("/faq", name="faq")
     *
     */
    public function FAQ()
    {
        return $this->render('home/faq.html.twig');
    }

    /**
     * @Route("/presentation", name="presentation")
     *
     * @return void
     */
    public function presentation()
    {
        return $this->render('home/presentation.html.twig');
    }

    /**
     * @Route("/les-tarifs", name="price")
     *
     * @return void
     */
    public function price()
    {
        return $this->render('home/price.html.twig');
    }
}
