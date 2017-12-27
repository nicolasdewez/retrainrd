# User

## Attributes

Title             | Type   | Persist | Default     | Comment
------------------| -------|---------|-------------|-------------------------
id                | int    | yes     |             |
email             | string | yes     |             |
password          | string | yes     |             |
firstName         | string | yes     |             |
lastName          | string | yes     |             |
roles             | array  | yes     | [ROLE_USER] | ROLE_ADMIN or ROLE_USER
enabled           | bool   | yes     | false       |
registrationState | string | yes     | created     |
registrationCode  | string | yes     |             |
emailNotification | bool   | yes     | true        |
currentPassword   | string | no      |             |
newPassword       | string | no      |             |


## Validation

### Registration (registration)

Attribute         | Asserts
------------------| --------------------------------------
id                | 
email             | Unique, NotBlank, Length(6-255), Email
password          | 
firstName         | NotBlank, Length(2-50)
lastName          | NotBlank, Length(2-50)
roles             | 
enabled           | 
registrationState | 
registrationCode  | 
emailNotification | 
currentPassword   | 
newPassword       | 


### Active user (active)

Attribute         | Asserts
------------------| --------------------------------
id                | 
email             | 
password          | 
firstName         | 
lastName          | 
roles             | 
enabled           | 
registrationState | 
registrationCode  | 
emailNotification | 
currentPassword   | 
newPassword       | NotEqualTo(password), Length(6-)


### Administrator create (admin_create)

Attribute         | Asserts
------------------| --------------------------------------
id                | 
email             | Unique, NotBlank, Length(6-255), Email
password          | NotBlank, Length(6-)
firstName         | NotBlank, Length(2-50)
lastName          | NotBlank, Length(2-50)
roles             | 
enabled           | 
registrationState | 
registrationCode  | 
emailNotification | 
currentPassword   | 
newPassword       | 


### My account (my_account)

Attribute         | Asserts
------------------| --------------------------------
id                | 
email             | 
password          | 
firstName         | NotBlank, Length(2-50)
lastName          | NotBlank, Length(2-50)
roles             | 
enabled           | 
registrationState | 
registrationCode  | 
emailNotification | 
currentPassword   | UserPassword
newPassword       | NotEqualTo(password), Length(6-)
