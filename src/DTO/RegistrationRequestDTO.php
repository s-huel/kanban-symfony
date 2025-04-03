<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class RegistrationRequestDTO
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 6)]
    public string $password;

    public function getEmail(): string
    {
        return $this->email;
    }
}