<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\MyPassword;
use App\Form\RegisterType;
use App\Form\MyPasswordType;
use App\Service\UserService;
use App\Service\MailerService;
use App\Form\RenewPasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    private $encoder;
    private $userService;
    private $mailerService;

    public function __construct(UserPasswordEncoderInterface $encoder, UserService $userService, MailerService $mailerService)
    {
        $this->encoder = $encoder;
        $this->userService = $userService;
        $this->mailerService = $mailerService;
    }

    /**
     * @Route("/me-connecter", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/me-deconnecter", name="logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }


    /**
     * Permet à l'utilisateur de s'enregistrer
     * 
     * @Route("/rejoindre-la-force", name="register")
     */
    public function register( Request $request ): Response
    {
        if( $this->getUser() ){
            return $this->redirectToRoute('home');
        }

        $user = new User();
        $form = $this->createForm( RegisterType::class, $user );

        $form->handleRequest( $request );
        
        if( $form->isSubmitted() && $form->isValid() )
        {
            // Vérification de l'age de l'utilisateur
            $age = $user->getBirthdate();
            
            if ((new \Datetime())->diff($age)->format('%Y') < 12 )
            {
                $this->addFlash('warning', 'Vous devez avoir 12 ans et plus pour vous inscrire.');
                return $this->redirectToRoute('register');
            }

            $password = $this->encoder->encodePassword( $user, $user->getPassword() );
            $user->setPassword( $password );

            $this->userService->generateToken( $user );

            $em = $this->getDoctrine()->getManager();
            $em->persist( $user );
            $em->flush();

            $this->mailerService->sendActivationMail( $user );

            $this->addFlash( 'info', 'Votre compte à bien été créé, activez le pour pouvoir vous connecter' );
            return $this->redirectToRoute( 'login' );
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    
    /**
     * Permet à l'utilisateur d'activer son compte
     *
     * @Route("/activation/{token}", name="activate")
     * 
     */
    public function activate( User $user ): Response
    {
        if(! $user->getEnabled())
        {
            if ($user->getExpiredToken() > new \DateTime())
            {
                $user->setEnabled(true);
                $this->userService->resetToken($user);

                $em = $this->getDoctrine()->getManager();
                $em->flush($user);

                $this->addFlash(
                    'info',
                    'Votre compte a été activé');
            } else {
                $url = $this->urlGenerator->generate( 'sendActivateToken', ['id' => $user->getId()], UrlGenerator::ABSOLUTE_URL);

                $this->addFlash(
                    'danger',
                    'Ce lien a expiré <a href="'.$url.'"> Renvoyer le mail d\'activation </a>');
            }
        }
        // redirect to login route
        return $this->redirectToRoute('login');
    }

    /**
     * Permet l'envoi d'un nouveau mail d'activation
     *
     * @Route("/reactivation/{id}", name="sendActivateToken")
     * 
     */
    public function sendActivaTetoken (User $user): Response
    {
        if( !$user->getEnabled() )
        {
            $this->userService->generateToken($user);

            $em = $this->getDoctrine()->getManager();
            $em->flush($user);

            $this->mailerService->sendActivationMail($user);

            $this->addFlash(
                    'info',
                    'Un lien d\'activation vous a été envoyé');
                    
            return $this->redirectToRoute('login');
        }
    }

    
    /**
     * Permet d'initier la méthode du mot de passe oublié
     *
     * @Route("/mot-de-passe-oublie", name="forget_password")
     *
     * @param Request $request
     * @return Response
     */
    public function forgetPassword(Request $request): Response
    {   
        if ($request->isMethod('POST'))
        {
            $email = $request->request->get('email');

            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneByEmail($email);

            if ($user) {
                $this->userService->generateToken( $user );
                $entityManager->flush();

                $this->mailerService->sendResetPassword( $user );
            }

            $this->addFlash('info', 'Si un compte existe avec cette adresse email, un email vous sera envoyé.');
            return $this->redirectToRoute('home');
        }
        return $this->render('security/forgotten_password.html.twig');
    }

    /**
     * Permet de réintialiser le mot de passe
     *
     * @Route("/reinitialisation-mot-de-passe/{token}", name="reset_password")
     *
     * @param string $token
     * @param Request $request
     * @return Response
     */
    public function resetPassword(string $token, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->findOneByToken($token);

        if ($user == null) {
            // A rediriger vers la 404
            return $this->redirectToRoute('home');
        }

        $myPassword = new MyPassword;

        $form = $this->createForm(MyPasswordType::class, $myPassword);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            if ($user->getExpiredToken() < new \DateTime())
            {
                $this->addFlash('alert', 'Votre token a expiré.');
            }
            else
            {
                $user->setPassword($this->encoder->encodePassword($user, $myPassword->getPassword()));
                $this->userService->resetToken($user);
                $entityManager->flush();

                $this->addFlash('success', 'Le mot de passe a bien été modifié.');
            }
            return $this->redirectToRoute('home');
        }

        return $this->render('security/reset_password.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet de changer de mot de passe
     * 
     * @Route("/nouveau-mot-de-passe", name="new_password", methods={"GET", "POST"})
     * 
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function newPassword(Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(RenewPasswordType::class, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form["password"]->getData()["password"];
            $user->setPassword($this->encoder->encodePassword($user, $newPassword));

            $this
                ->getDoctrine()
                ->getManager()
                ->flush();

            $this->addFlash("success", "Votre mot de passe a bien été modifié.");

            return $this->redirectToRoute("home");
        }

        return $this->render("security/renew_password.html.twig", [
            "form" => $form->createView(),
        ]);
    }
}
