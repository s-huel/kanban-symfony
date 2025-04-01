<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateLaneRequestDTO
{
    #[Assert\NotBlank]
    public ?string $title;
}