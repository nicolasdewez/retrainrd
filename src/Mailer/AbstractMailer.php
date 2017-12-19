<?php

namespace App\Mailer;

use App\Translation\Locale;
use Psr\Log\LoggerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Environment as Twig;

abstract class AbstractMailer
{
    /** @var \Swift_Mailer */
    protected $mailer;

    /** @var TranslatorInterface */
    private $translator;

    /** @var Twig */
    protected $twig;

    /** @var LoggerInterface */
    protected $logger;

    public function __construct(\Swift_Mailer $mailer, TranslatorInterface $translator, Twig $twig, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->twig = $twig;
        $this->logger = $logger;
    }

    protected function buildMessage(string $to, string $subject, string $body): \Swift_Message
    {
        return (new \Swift_Message())
            ->setFrom(Mail::SENDER)
            ->setReplyTo(Mail::REPLY_TO)
            ->setTo($to)->setSubject(
                $this->translator->trans(
                    $subject,
                    [],
                    Mail::TRANSLATOR_DOMAIN,
                    Locale::FR
                )
            )
            ->setBody($body, Mail::CONTENT_TYPE)
        ;
    }
}
