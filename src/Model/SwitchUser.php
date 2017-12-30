<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class SwitchUser
{
    /**
     * @var string
     *
     * @Assert\NotBlank
     * @Assert\Email
     */
    private $email;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}
