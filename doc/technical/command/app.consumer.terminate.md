# Terminate consumer

## Use case

Stop all consumers which execute process registration.

## Command

```bash
bin/console app:consumer:terminate
```

## Arguments

* consumer: name of the consumer (registration, password_lost etc.)

## Options

No options.


## Example

```bash
bin/console app:consumer:terminate registration
```

### Display on success

```
Signal SIGTERM sent to PID 118
1 consumer were terminated
```

### Display on error

```
No consumer process found
```

```
Consumer test not found or its producer
```
