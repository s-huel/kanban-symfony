<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UserLoginDTO
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    public string $password;
}