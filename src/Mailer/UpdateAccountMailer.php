<?php

namespace App\Mailer;

use App\Entity\User;
use App\Logger\Log;

class UpdateAccountMailer extends AbstractMailer
{
    public function execute(User $user): void
    {
        $body = $this->twig->render(
            'mailing/update-account.html.twig',
            [
                'user' => $user,
            ]
        );

        $message = $this->buildMessage(
            $user->getEmail(),
            Mail::SUBJECT_UPDATE_ACCOUNT,
            $body
        );

        $this->mailer->send($message);

        $this->logger->info(sprintf('[%s] Mail sent', Log::SUBJECT_UPDATE_ACCOUNT));
    }
}
