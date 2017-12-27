# Process of registration

## Account creation

* When an user is registered, the value of the field `registrationState` is `registered`. 
* A message is published in queue `registration`
* The consumer generates a new registration code and send an email.

## Account activation

* When the user click on the link for active the account, a form is displayed for choose a password.
* User is enabled and the value of the field `registrationState` is `activated`.
