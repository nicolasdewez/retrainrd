<?php

namespace App\Controller\Security;

use App\Form\Type\LoginType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment as Twig;

class ConnectionController
{
    /**
     * @Route("/connect", name="app_connection", methods={"GET"})
     */
    public function loginAction(FormFactoryInterface $formFactory, Twig $twig, AuthenticationUtils $authenticationUtils): Response
    {
        $form = $formFactory->create(LoginType::class);

        return new Response(
            $twig->render('security/connection/login.html.twig', [
                'form' => $form->createView(),
                'error' => $authenticationUtils->getLastAuthenticationError(),
            ])
        );
    }
}
