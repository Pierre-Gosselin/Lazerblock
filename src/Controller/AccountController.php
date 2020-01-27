<?php

namespace App\Controller;

use App\Form\UpdateUserType;
use App\Repository\AvatarRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/moncompte", name="account")
 */
class AccountController extends AbstractController
{
    /**
     * Permet d'afficher le profil de l'utilisateur
     * 
     * @Route("/", name="_show", methods={"GET", "POST"})
     * 
     * @return Response
     */
    public function index()
    {
        return $this->render('account/index.html.twig');
    }
    

    /**
     * Permet d'afficher et de gerer la modification du profil
     * 
     * @Route("/editer", name="_edit")
     *
     * @param Request $request
     * @return Response
     */
    public function update(Request $request, AvatarRepository $avatarRepository)
    {
        $user = $this->getUser();

        $avatars = $avatarRepository->findAll();

        $form = $this->createForm(UpdateUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $avatar = $avatarRepository->findOneById($request->request->get("avatar"));
            $manager = $this->getDoctrine()->getManager();

            $user->setAvatar($avatar);

            $manager->flush();

            $this->addFlash('success', "Votre profil a bien été mis à jour.");
            return $this->redirectToRoute('account_show');
        }

        return $this->render('account/update.html.twig', [
            'form' => $form->createView(),
            'avatars' => $avatars,
        ]);
    }
}
