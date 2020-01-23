<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="account", methods={"GET", "POST"})
     */
    public function index( Request $request, UserRepository$userRepository )
    {
        return $this->render('account/index.html.twig');
    }
}
