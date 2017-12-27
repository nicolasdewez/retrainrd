<?php

namespace App\Mailer;

use Psr\Log\LoggerInterface;
use Twig\Environment as Twig;

abstract class AbstractMailer
{
    /** @var \Swift_Mailer */
    protected $mailer;

    /** @var Twig */
    protected $twig;

    /** @var LoggerInterface */
    protected $logger;

    public function __construct(\Swift_Mailer $mailer, Twig $twig, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->logger = $logger;
    }

    protected function buildMessage(string $to, string $subject, string $body): \Swift_Message
    {
        return (new \Swift_Message())
            ->setFrom(Mail::SENDER)
            ->setReplyTo(Mail::REPLY_TO)
            ->setTo($to)->setSubject($subject)
            ->setBody($body, Mail::CONTENT_TYPE)
        ;
    }
}
