<?php

namespace App\Session;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\TranslatorInterface;

class FlashMessage
{
    /** @var Session */
    private $session;

    /** @var TranslatorInterface */
    private $translator;

    public function __construct(SessionInterface $session, TranslatorInterface $translator)
    {
        $this->session = $session;
        $this->translator = $translator;
    }

    public function add(string $type, string $message, array $parameters = null)
    {
        if (null !== $parameters) {
            $message = $this->translator->trans($message, $parameters);
        }

        $this->session->getFlashBag()->add($type, $message);
    }
}
