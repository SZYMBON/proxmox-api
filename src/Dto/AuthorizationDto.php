<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class AuthorizationDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]

        public readonly ?string $email,
        #[Assert\NotBlank]
        #[Assert\Length(
            min: 6,
            minMessage: 'Your password must be at least {{ limit }} characters long',
        )]
        public readonly ?string $password
    ){
    }
}