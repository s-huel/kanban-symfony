<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateTaskRequestDTO
{
    #[Assert\NotBlank]
    public ?string $title = null;

    #[Assert\NotBlank]
    public ?int $lane_id = null;
}
