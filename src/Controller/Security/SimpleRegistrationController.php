<?php

namespace App\Controller\Security;

use App\Form\Type\ActiveUserType;
use App\Form\Type\RegistrationType;
use App\Security\ActiveUser;
use App\Security\AskRegistration;
use App\Security\CheckRegistrationCode;
use App\Session\Flash;
use App\Session\FlashMessage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment as Twig;

class SimpleRegistrationController
{
    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var Twig */
    private $twig;

    /** @var RouterInterface */
    private $router;

    /** @var FlashMessage */
    private $flashMessage;

    public function __construct(
        FormFactoryInterface $formFactory,
        Twig $twig,
        RouterInterface $router,
        FlashMessage $flashMessage
    ) {
        $this->formFactory = $formFactory;
        $this->twig = $twig;
        $this->router = $router;
        $this->flashMessage = $flashMessage;
    }

    /**
     * @Route("/registration", name="app_simple_registration", methods={"GET", "POST"})
     */
    public function registrationAction(Request $request, AskRegistration $askRegistration): Response
    {
        $form = $this->formFactory->create(RegistrationType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $askRegistration->execute($form->getData());

            $this->flashMessage->add(Flash::TYPE_NOTICE, 'registration.ok');

            return new RedirectResponse($this->router->generate('app_connection'));
        }

        return new Response(
            $this->twig->render('security/simple-registration/registration.html.twig', [
                'form' => $form->createView(),
            ])
        );
    }

    /**
     * @Route("/active/{registrationCode}", name="app_simple_registration_active", methods={"GET", "POST"})
     */
    public function activeAction(string $registrationCode, Request $request, CheckRegistrationCode $checker, ActiveUser $activeUser): Response
    {
        if (!$checker->execute($registrationCode)) {
            throw new AccessDeniedHttpException('Access denied for user. Registration code or user are invalid.');
        }

        $user = $checker->getUser();

        $form = $this->formFactory->create(ActiveUserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $activeUser->execute($user);
            $this->flashMessage->add(Flash::TYPE_NOTICE, 'active.ok');

            return new RedirectResponse($this->router->generate('app_connection'));
        }

        return new Response(
            $this->twig->render('security/simple-registration/active.html.twig', [
                'form' => $form->createView(),
            ])
        );
    }
}
