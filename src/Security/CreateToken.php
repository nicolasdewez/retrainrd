<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class CreateToken
{
    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var SessionInterface */
    private $session;

    public function __construct(TokenStorageInterface $tokenStorage, SessionInterface $session)
    {
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
    }

    public function execute(User $user)
    {
        $token = new UsernamePasswordToken($user, null, Token::FIREWALL_NAME, $user->getRoles());
        $this->tokenStorage->setToken($token);

        $this->session->set(Token::SESSION_SECURITY_ATTRIBUTE, serialize($token));
        $this->session->save();
    }
}
