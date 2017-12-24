<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\Type\MyAccountType;
use App\Form\Type\PasswordLostType;
use App\Security\AskPasswordLost;
use App\Security\UpdateAccount;
use App\Session\Flash;
use App\Session\FlashMessage;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment as Twig;

class AccountController
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
     * @Route("/password-lost", name="app_account_password_lost", methods={"GET", "POST"})
     */
    public function passwordLostAction(Request $request, AskPasswordLost $askPasswordLost): Response
    {
        $form = $this->formFactory->create(PasswordLostType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $askPasswordLost->execute($form->getData());

            $this->flashMessage->add(Flash::TYPE_NOTICE, 'password_lost.ok');

            return new RedirectResponse($this->router->generate('app_connection'));
        }

        return new Response(
            $this->twig->render('security/account/password-lost.html.twig', [
                'form' => $form->createView(),
            ])
        );
    }

    /**
     * @Route("/my-account", name="app_account_my_account", methods={"GET", "POST"})
     */
    public function myAccountAction(Request $request, UserInterface $user, UpdateAccount $updateAccount): Response
    {
        $form = $this->formFactory->create(MyAccountType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $updateAccount->execute($user);

            $this->flashMessage->add(Flash::TYPE_NOTICE, 'my_account.ok');

            return new RedirectResponse($this->router->generate('app_account_my_account'));
        }

        return new Response(
            $this->twig->render('security/account/my-account.html.twig', [
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
            $this->twig->render('security/account/user-bar.html.twig', [
                'username' => $user->getUsername(),
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),
                'isAdmin' => $user->isAdmin(),
            ])
        );
    }
}
