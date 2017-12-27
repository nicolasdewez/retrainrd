<?php

namespace App\Workflow;

final class RegistrationDefinitionWorkflow
{
    const PLACE_CREATED = 'created';
    const PLACE_REGISTERED = 'registered';
    const PLACE_ACTIVATED = 'activated';

    const TRANSITION_REGISTRATION = 'registration';
    const TRANSITION_ACTIVE = 'active';
    const TRANSITION_PASSWORD_LOST = 'password_lost';

    const PLACE_TITLE_CREATED = 'Créé';
    const PLACE_TITLE_REGISTERED = 'Enregistré';
    const PLACE_TITLE_ACTIVATED = 'Activé';

    const TITLES_PLACES = [
        self::PLACE_CREATED => self::PLACE_TITLE_CREATED,
        self::PLACE_REGISTERED => self::PLACE_TITLE_REGISTERED,
        self::PLACE_ACTIVATED => self::PLACE_TITLE_ACTIVATED,
    ];

    public static function getTitleByPlace(string $place): ?string
    {
        if (!isset(self::TITLES_PLACES[$place])) {
            return null;
        }

        return self::TITLES_PLACES[$place];
    }
}
