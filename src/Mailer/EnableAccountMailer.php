<?php

namespace App\Mailer;

use App\Entity\User;
use App\Logger\Log;

class EnableAccountMailer extends AbstractMailer
{
    public function execute(User $user)
    {
        $body = $this->twig->render(
            'mailing/enable-account.html.twig',
            [
                'user' => $user,
            ]
        );

        $message = $this->buildMessage(
            $user->getEmail(),
            Mail::SUBJECT_ENABLE_ACCOUNT,
            $body
        );

        $this->mailer->send($message);

        $this->logger->info(sprintf('[%s] Mail sent', Log::SUBJECT_ENABLE_ACCOUNT));
    }
}
