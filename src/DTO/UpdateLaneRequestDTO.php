<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

// DTO for updating a lane
class UpdateLaneRequestDTO
{
    #[Assert\NotBlank]
    public ?string $title;
}