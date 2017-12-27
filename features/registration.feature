@reset-schema
@reset-queue
Feature:
    Check process of registration

    Scenario:
          There are no users in database.
          An user wants to register.

        Given I go to "/"
        Then I should see "Bienvenue"
        When I follow "Inscription"
        Then I should see "Inscription"
        When I fill in "registration_firstName" with "Nicolas"
        And I fill in "registration_lastName" with "Dewez"
        And I fill in "registration_email" with "ndewez@example.com"
        And I press "Enregistrer"
        Then the url should match "/connect"
        And I should see "Votre demande d'inscription est en cours"
        And 1 user should have been created
        And should be 1 user like:
            | id | email              | firstName | lastName | enabled |
            | 1  | ndewez@example.com | Nicolas   | Dewez    | false   |
        And the queue associated to "registration" producer has messages to re-publish below:
            | 1 | {"id":1} |
        When I run the app command "rabbitmq:consumer registration --messages=1"
        Then the queue associated to "registration" producer is empty
        When I run the app command "swiftmailer:spool:send"
        Then I should see "1 emails sent" in the output of command
        And I should see mail with subject "[Retrainrd] Inscription"
        When I open mail with subject "[Retrainrd] Inscription"
        Then I should see "Pour activer votre compte, merci de cliquer sur ce lien" in mail
        When I follow "ici" in mail
        Then I should see "Définition du mot de passe"
        When I fill in "active_user_newPassword_first" with "password1"
        And I fill in "active_user_newPassword_second" with "password1"
        And I press "Enregistrer"
        Then the url should match "/connect"
        And should be 1 user like:
            | id | email              | firstName | lastName | enabled |
            | 1  | ndewez@example.com | Nicolas   | Dewez    | true    |
        When I fill in "_username" with "ndewez@example.com"
        And I fill in "_password" with "password1"
        And I press "Se connecter"
        # To change it
        And I should see "home.title"
