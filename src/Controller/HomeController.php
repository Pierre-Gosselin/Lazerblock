<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig');
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
}
