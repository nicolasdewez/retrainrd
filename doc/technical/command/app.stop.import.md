# Import all stops

## Use case

Import (create or edit) all stops.

## Command

```bash
bin/console app:stop:import
```

## Arguments

No arguments.

## Options

No options.


## Example

```bash
bin/console app:stop:import
```

### Display on success

```
Call api "get stop areas"
> 3026 stops to processed
Create or edit each entity
Save in database
Process finished
```

### Display on error

```
Call api "get stop areas"

In CurlFactory.php line 186:
                                                                                                                                                  
  cURL error 28: Operation timed out after 2134 milliseconds with 0 out of 0 bytes received (see http://curl.haxx.se/libcurl/c/libcurl-errors.html) 
```
