<?php

namespace App\DTO;

use AllowDynamicProperties;
use Symfony\Component\Validator\Constraints as Assert;

#[AllowDynamicProperties] class UserLoginRequestDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 4, max: 180)]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 6)]
    public string $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }
}
