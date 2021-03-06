<?php

namespace App\Entity;

use App\Security\Role;
use App\Workflow\RegistrationDefinitionWorkflow;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(
 *     name="users",
 *     indexes={
 *         @ORM\Index(name="user_email", columns={"email"}),
 *         @ORM\Index(name="user_registration_code", columns={"registration_code"})
 *     }
 * )
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *
 * @UniqueEntity(fields={"email"}, groups={"registration", "admin_create"})
 */
class User implements AdvancedUserInterface
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", options={"unsigned": true})
     *
     * @Serializer\Groups({
     *     "event_registration",
     *     "event_update_account",
     *     "event_enable_account",
     *     "event_disable_account",
     *     "event_password_lost"
     * })
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(unique=true)
     *
     * @Assert\NotBlank(groups={"registration", "admin_create"})
     * @Assert\Length(min=6, max=255, groups={"registration", "admin_create"})
     * @Assert\Email(groups={"registration", "admin_create"})
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column
     *
     * @Assert\NotBlank(groups={"admin_create"})
     * @Assert\Length(min=6, groups={"admin_create"})
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(length=50)
     *
     * @Assert\NotBlank(groups={"my_account", "registration", "admin_create", "admin_edit", "user_edit"})
     * @Assert\Length(min=2, max=50, groups={"my_account", "registration", "admin_create", "admin_edit", "user_edit"})
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(length=50)
     *
     * @Assert\NotBlank(groups={"my_account", "registration", "admin_create", "admin_edit", "user_edit"})
     * @Assert\Length(min=2, max=50, groups={"my_account", "registration", "admin_create", "admin_edit", "user_edit"})
     */
    private $lastName;

    /**
     * @var array
     *
     * @ORM\Column(type="simple_array")
     *
     * @Assert\NotBlank(groups={"edit"})
     * @Assert\Choice(callback={"App\Security\Role", "getRoles"}, strict=true, multiple=true, groups={"edit"})
     */
    private $roles;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @var string
     *
     * @ORM\Column(length=15)
     */
    private $registrationState;

    /**
     * @var string
     *
     * @ORM\Column
     */
    private $registrationCode;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $emailNotification;

    /**
     * Used in form.
     *
     * @var string
     *
     * SecurityAssert\UserPassword(groups={"my_account"})
     */
    private $currentPassword;

    /**
     * Used in form.
     *
     * @var string
     *
     * @Assert\NotBlank(groups={"active"})
     * @Assert\NotEqualTo(value="password", groups={"active", "my_account"})
     * @Assert\Length(min=6, groups={"active", "my_account"})
     */
    private $newPassword;

    public function __construct()
    {
        $this->roles = [Role::USER];
        $this->enabled = false;
        $this->registrationState = RegistrationDefinitionWorkflow::PLACE_CREATED;
        $this->emailNotification = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function isUser(): bool
    {
        return in_array(Role::USER, $this->roles);
    }

    public function isAdmin(): bool
    {
        return in_array(Role::ADMIN, $this->roles);
    }

    public function isSuperAdmin(): bool
    {
        return in_array(Role::SUPER_ADMIN, $this->roles);
    }

    public function setSuperAdmin(bool $superAdmin): void
    {
        // Set user as a super admin!
        if ($superAdmin) {
            // User is already super admin
            if ($this->isSuperAdmin()) {
                return;
            }

            $this->roles[] = Role::SUPER_ADMIN;

            return;
        }

        // Don't set user as a super admin

        // User isn't a super admin
        if (!$this->isSuperAdmin()) {
            return;
        }

        // Delete role
        $roles = array_filter($this->roles, function ($role) {
            return Role::SUPER_ADMIN !== $role;
        });

        $this->roles = $roles;
    }

    public function getTitleRoles(): string
    {
        $roles = [];

        foreach ($this->roles as $role) {
            $roles[] = Role::getTitleByRole($role);
        }

        return implode(', ', $roles);
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    public function getRegistrationState(): string
    {
        return $this->registrationState;
    }

    public function setRegistrationState(string $registrationState): void
    {
        $this->registrationState = $registrationState;
    }

    public function getTItleRegistrationState(): ?string
    {
        return RegistrationDefinitionWorkflow::getTitleByPlace($this->registrationState);
    }

    public function getRegistrationCode(): ?string
    {
        return $this->registrationCode;
    }

    public function setRegistrationCode(string $registrationCode): void
    {
        $this->registrationCode = $registrationCode;
    }

    public function isEmailNotification(): bool
    {
        return $this->emailNotification;
    }

    public function setEmailNotification(bool $emailNotification): void
    {
        $this->emailNotification = $emailNotification;
    }

    public function getCurrentPassword(): ?string
    {
        return $this->currentPassword;
    }

    public function setCurrentPassword(string $currentPassword)
    {
        $this->currentPassword = $currentPassword;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): void
    {
        $this->newPassword = $newPassword;
    }

    public function isAccountNonExpired(): bool
    {
        return true;
    }

    public function isAccountNonLocked(): bool
    {
        return true;
    }

    public function isCredentialsNonExpired(): bool
    {
        return true;
    }

    public function getSalt(): string
    {
        return '';
    }

    public function eraseCredentials(): void
    {
    }

    public function getUsername(): string
    {
        return $this->email;
    }
}
