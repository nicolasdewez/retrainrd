<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\ActiveUserType;
use App\Form\Type\LoginType;
use App\Form\Type\MyAccountType;
use App\Form\Type\PasswordLostType;
use App\Form\Type\RegistrationType;
use App\Security\ActiveUser;
use App\Security\AskPasswordLost;
use App\Security\AskRegistration;
use App\Security\CheckRegistrationCode;
use App\Security\UpdateAccount;
use App\Session\Flash;
use App\Session\FlashMessage;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment as Twig;

class SecurityController
{
    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var Twig */
    private $twig;

    /** @var RouterInterface */
    private $router;

    /** @var FlashMessage */
    private $flashMessage;

    /**
     * @param FormFactoryInterface $formFactory
     * @param Twig                 $twig
     * @param RouterInterface      $router
     * @param FlashMessage         $flashMessage
     */
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
     * @Route("/registration", name="app_registration", methods={"GET", "POST"})
     */
    public function registrationAction(Request $request, AskRegistration $askRegistration): Response
    {
        $form = $this->formFactory->create(RegistrationType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $askRegistration->execute($form->getData());

            $this->flashMessage->add(Flash::TYPE_NOTICE, 'registration.ok');

            return new RedirectResponse($this->router->generate('app_login'));
        }

        return new Response(
            $this->twig->render('security/registration.html.twig', [
                'form' => $form->createView(),
            ])
        );
    }

    /**
     * @Route("/active/{registrationCode}", name="app_active", methods={"GET", "POST"})
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

            return new RedirectResponse($this->router->generate('app_login'));
        }

        return new Response(
            $this->twig->render('security/active.html.twig', [
                'form' => $form->createView(),
            ])
        );
    }

    /**
     * @Route("/password-lost", name="app_password_lost", methods={"GET", "POST"})
     */
    public function passwordLostAction(Request $request, AskPasswordLost $askPasswordLost): Response
    {
        $form = $this->formFactory->create(PasswordLostType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $askPasswordLost->execute($form->getData());

            $this->flashMessage->add(Flash::TYPE_NOTICE, 'password_lost.ok');

            return new RedirectResponse($this->router->generate('app_login'));
        }

        return new Response(
            $this->twig->render('security/password-lost.html.twig', [
                'form' => $form->createView(),
            ])
        );
    }

    /**
     * @Route("/login", name="app_login", methods={"GET"})
     */
    public function loginAction(AuthenticationUtils $authenticationUtils): Response
    {
        $form = $this->formFactory->create(LoginType::class);

        return new Response(
            $this->twig->render('security/login.html.twig', [
                'form' => $form->createView(),
                'error' => $authenticationUtils->getLastAuthenticationError(),
            ])
        );
    }

    /**
     * @Route("/my-account", name="app_my_account", methods={"GET", "POST"})
     */
    public function myAccountAction(Request $request, UserInterface $user, UpdateAccount $updateAccount): Response
    {
        $form = $this->formFactory->create(MyAccountType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $updateAccount->execute($user);

            $this->flashMessage->add(Flash::TYPE_NOTICE, 'my_account.ok');

            return new RedirectResponse($this->router->generate('app_my_account'));
        }

        return new Response(
            $this->twig->render('security/my-account.html.twig', [
                'form' => $form->createView(),
            ])
        );
    }

    public function userBarAction(LoggerInterface $logger, UserInterface $user = null): Response
    {
        if (null === $user) {
            return new Response();
        }

        if (!($user instanceof User)) {
            $logger->error(sprintf(
                'User %s is not a valid user (User instance expected, %s found)',
                $user->getUsername(),
                get_class($user)
            ));

            return new Response();
        }

        return new Response(
            $this->twig->render('common/user-bar.html.twig', [
                'username' => $user->getUsername(),
                'firstname' => $user->getFirstname(),
                'lastname' => $user->getLastname(),
                'isAdmin' => $user->isAdmin(),
            ])
        );
    }
}
