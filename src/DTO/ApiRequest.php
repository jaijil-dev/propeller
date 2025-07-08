<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ApiRequest
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public ?string $email = null;

    #[Assert\Length(max: 255)]
    public ?string $first_name = null;

    #[Assert\Length(max: 255)]
    public ?string $last_name = null;

    #[Assert\NotBlank]
    #[Assert\Date]
    public ?string $date_of_birth = null;

    public ?bool $marketing_consent = false;

    #[Assert\Type('array')]
    #[Assert\All([
        new Assert\Choice(choices: ['London', 'Birmingham', 'Edinburgh'])
    ])]
    public array $marketing_lists_raw = [];

    #[Assert\NotBlank]
    #[Assert\Length(min: 5)]
    public ?string $message = null;
}
