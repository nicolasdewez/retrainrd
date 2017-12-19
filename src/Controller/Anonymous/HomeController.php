<?php

namespace App\Controller\Anonymous;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment as Twig;

class HomeController
{
    /**
     * @Route("/", name="app_anonymous_home", methods={"GET"})
     */
    public function indexAction(Twig $twig): Response
    {
        return new Response(
            $twig->render('anonymous/home/index.html.twig')
        );
    }
}
