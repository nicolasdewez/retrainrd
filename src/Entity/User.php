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
 * @ORM\Table(indexes={
 *     @ORM\Index(name="user_username", columns={"username"}),
 *     @ORM\Index(name="user_registration_code", columns={"registration_code"})
 * })
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *
 * @UniqueEntity(fields={"username"}, groups={"registration", "admin_create"})
 */
class User implements AdvancedUserInterface
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
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
     * @ORM\Column(length=30, unique=true)
     *
     * @Assert\NotBlank(groups={"registration", "admin_create"})
     * @Assert\Length(min=4, max=30, groups={"registration", "admin_create"})
     * @Assert\Regex(pattern="/^[a-z0-9_]+$/i", groups={"registration", "admin_create"})
     */
    private $username;

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
     * @var string
     *
     * @ORM\Column
     *
     * @Assert\NotBlank(groups={"my_account", "registration", "admin_create"})
     * @Assert\Length(min=6, max=255, groups={"my_account", "registration", "admin_create"})
     * @Assert\Email(groups={"my_account", "registration", "admin_create"})
     */
    private $email;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
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

    public function setNewPassword(string $newPassword)
    {
        $this->newPassword = $newPassword;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }
}
