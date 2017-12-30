# Create an administrator

## Use case

Used for create an administrator.

## Command

```bash
bin/console app:admin:create
```

## Arguments

* email: it must be unique
* password: password no encoded

## Options

* --super-admin / -s: create a super admin


## Examples

```bash
bin/console app:admin:create ndewez@retrainrd.com ndewez
bin/console app:admin:create ndewez@retrainrd.com ndewez -s
```

### Display on success

```
User created.
```

### Display on error

```
User is not valid.
Object(App\Entity\User).email:
    Cette valeur est déjà utilisée. (code 23bd9dbf-6b9b-41cd-a99e-4844bcf3077f)
```
