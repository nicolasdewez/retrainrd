<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment as Twig;

/**
 * @Route("/user")
 */
class HomeController
{
    /**
     * @Route("", name="app_user_home", methods={"GET"})
     */
    public function testAction(Twig $twig): Response
    {
        return new Response(
            $twig->render('home/index.html.twig')
        );
    }
}
