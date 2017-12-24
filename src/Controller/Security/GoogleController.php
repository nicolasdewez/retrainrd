<?php

namespace App\Controller\Security;

use App\Manager\UserManager;
use App\Security\CreateToken;
use KnpU\OAuth2ClientBundle\Client\OAuth2Client;
use League\OAuth2\Client\Provider\GoogleUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

/**
 * @Route("/connect/google")
 */
class GoogleController
{
    /** @var OAuth2Client */
    private $client;

    public function __construct(OAuth2Client $client)
    {
        $this->client = $client;
    }

    /**
     * @Route("", name="app_connection_google")
     */
    public function connectAction(): RedirectResponse
    {
        return $this->client->redirect();
    }

    /**
     * @Route("/check", name="app_connection_google_check")
     */
    public function connectCheckAction(UserManager $userManager, CreateToken $createToken, RouterInterface $router)
    {
        /** @var GoogleUser $googleUser */
        $googleUser = $this->client->fetchUser();

        $user = $userManager->getOrCreateUserFromGoogle($googleUser);
        $createToken->execute($user);

        return new RedirectResponse($router->generate('app_user_home'));
    }
}
