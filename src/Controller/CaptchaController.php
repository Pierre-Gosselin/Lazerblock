<?php

namespace App\Controller;

use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CaptchaController extends AbstractController
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
    
    /**
     * @Route("/captcha", name="captcha")
     */
    public function getCaptcha()
    {
        // Génération du captcha
        $captcha = bin2hex(openssl_random_pseudo_bytes(3));
        $this->session->set('captcha', $captcha);

        $headers = array('Content-Type' => 'image/png','Content-Disposition' => 'inline; filename="image.png"');
        
        $manager = new ImageManager(array('driver' => 'imagick'));
        $image = $manager->canvas(150, 40, '#FFFFFF');

        $image->text($captcha, 75, 15, function($font) {
            $font->file('build/fonts/Starjedi.cc3019aa.ttf');
            $font->size(30);
            $font->color('#000000');
            $font->align('center');
            $font->valign('center');
        });

        $image->blur(3);
        $image->contrast(25);

        // send HTTP header and output image data
        return new Response($image->encode('png'), 200, $headers);
    }
}