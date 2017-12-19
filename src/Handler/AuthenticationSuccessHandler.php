<?php

namespace App\Handler;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    const ROUTE_LOGIN = 'app_login';
    const DEFAULT_ROUTE_USER = 'app_user_home';
    const DEFAULT_ROUTE_ADMIN = 'admin';

    /** @var RouterInterface */
    protected $router;

    /** @var LoggerInterface */
    protected $logger;

    public function __construct(RouterInterface $router, LoggerInterface $logger)
    {
        $this->router = $router;
        $this->logger = $logger;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        $user = $token->getUser();
        if (!($user instanceof User)) {
            $this->logger->error(sprintf(
                'User %s is not a valid user (User instance expected, %s found)',
                $user->getUsername(),
                get_class($user)
            ));

            return new RedirectResponse($this->router->generate(self::ROUTE_LOGIN));
        }

        if ($user->isAdmin()) {
            return new RedirectResponse($this->router->generate(self::DEFAULT_ROUTE_ADMIN));
        }

        return new RedirectResponse($this->router->generate(self::DEFAULT_ROUTE_USER));
    }
}
