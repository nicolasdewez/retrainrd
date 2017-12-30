<?php

namespace App\Session;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FlashMessage
{
    /** @var Session */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function add(string $type, string $message): void
    {
        $this->session->getFlashBag()->add($type, $message);
    }
}
