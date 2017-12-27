<?php

namespace App\Mailer;

final class Mail
{
    const TRANSLATOR_DOMAIN = 'mails';

    const SENDER = 'contact@retrainrd.com';
    const REPLY_TO = 'contact@retrainrd.com';

    const CONTENT_TYPE = 'text/html';

    const SUBJECT_REGISTRATION = '[Retrainrd] Inscription';
    const SUBJECT_PASSWORD_LOST = '[Retrainrd] Mot de passe perdu';
    const SUBJECT_UPDATE_ACCOUNT = '[Retrainrd] Mise à jour de votre compte';
    const SUBJECT_ENABLE_ACCOUNT = '[Retrainrd] Réactivation de votre compte';
    const SUBJECT_DISABLE_ACCOUNT = '[Retrainrd] Désactivation de votre compte';
}
