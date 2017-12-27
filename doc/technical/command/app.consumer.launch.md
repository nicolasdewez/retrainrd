# Launch consumer

## Use case

Launch 2 consumers for execute process registration.

## Command

```bash
bin/console app:consumer:launch
```

## Arguments

* consumer: name of the consumer (registration, password_lost etc.)
* number: number of consumer to launch

## Options

No options.


## Example

```bash
bin/console app:consumer:launch registration 2
```

### Display on success

```
2 registration consumer(s) launched
```

### Display on error

No examples.
