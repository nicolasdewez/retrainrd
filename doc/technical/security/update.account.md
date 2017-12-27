# Process of update account

* When an user updated his information a message is published in queue `mail_update_account` (if the notifications are enabled for the user).
* The consumer send an email.
