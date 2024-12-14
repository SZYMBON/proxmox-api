<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class ProxmoxConnectionDto
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly ?string $name,

        #[Assert\NotBlank]
        public readonly ?string $hostname,

        #[Assert\NotBlank]
        public readonly ?string $ipAddress,

        #[Assert\NotBlank]
        public readonly ?string $username,

        #[Assert\NotBlank]
        public readonly string $password,

        #[Assert\NotBlank]
        public readonly ?string $authType,

        #[Assert\NotBlank]
        public readonly ?string $port,
    )
    {
    }
}