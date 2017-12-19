<?php

namespace App\Mailer;

final class Mail
{
    const TRANSLATOR_DOMAIN = 'mails';

    const SENDER = 'contact@retrainrd.com';
    const REPLY_TO = 'contact@retrainrd.com';

    const CONTENT_TYPE = 'text/html';

    const SUBJECT_REGISTRATION = 'registration.subject';
    const SUBJECT_PASSWORD_LOST = 'password_lost.subject';
    const SUBJECT_UPDATE_ACCOUNT = 'update_account.subject';
    const SUBJECT_ENABLE_ACCOUNT = 'enable_account.subject';
    const SUBJECT_DISABLE_ACCOUNT = 'disable_account.subject';
}
