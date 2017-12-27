# Stop

## Attributes

Title       | Type   | Persist | Default | Comment
------------| -------|---------|---------|---------
id          | int    | yes     |         |
code        | string | yes     |         |
name        | string | yes     |         |
label       | string | yes     |         |
coordinates | Point  | yes     |         |


## Validation

### All

Attribute   | Asserts
------------| -----------------------
id          | 
code        | NotBlank, Length(-30)
name        | NotBlank, Length(-255)
label       | NotBlank, Length(-255)
coordinates | NotBlank
