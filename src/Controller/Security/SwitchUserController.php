<?php

namespace App\Controller\Security;

use App\Form\Type\SwitchUserType;
use App\Model\SwitchUser;
use App\Session\Flash;
use App\Session\FlashMessage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment as Twig;

/**
 * @Route("/switch-user")
 */
class SwitchUserController
{
    const PARAM_SWITCH = '_switch_user';
    const PARAM_VALUE_EXIT_SWITCH = '_exit';

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var RouterInterface */
    private $router;

    /** @var Twig */
    private $twig;

    /** @var FlashMessage */
    private $flashMessage;

    public function __construct(FormFactoryInterface $formFactory, RouterInterface $router, Twig $twig, FlashMessage $flashMessage)
    {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->twig = $twig;
        $this->flashMessage = $flashMessage;
    }

    /**
     * @Route("/connect", name="app_switch_user_connect", methods={"GET", "POST"})
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function loginAction(Request $request): Response
    {
        $switchUser = new SwitchUser();
        $form = $this->formFactory->create(SwitchUserType::class, $switchUser);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $switchUser->getEmail();
            $this->flashMessage->add(Flash::TYPE_NOTICE, sprintf('Connexion au compte %s effectuée', $email));

            return new RedirectResponse($this->router->generate('app_user_home', [self::PARAM_SWITCH => $email]));
        }

        return new Response(
            $this->twig->render('security/connection/switch-user.html.twig', [
                'form' => $form->createView(),
            ])
        );
    }

    /**
     * @Route("/logout", name="app_switch_user_logout", methods={"GET"})
     * @Security("has_role('ROLE_PREVIOUS_ADMIN')")
     */
    public function logoutAction(): RedirectResponse
    {
        $this->flashMessage->add(Flash::TYPE_NOTICE, 'Déconnexion du compte réussie.');

        return new RedirectResponse(
            $this->router->generate('app_user_home', [self::PARAM_SWITCH => self::PARAM_VALUE_EXIT_SWITCH])
        );
    }
}
