<?php

namespace App\Entity;

use App\Repository\ProxmoxConnectionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProxmoxConnectionRepository::class)]
final class ProxmoxConnection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('ProxmoxConnectionController')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('ProxmoxConnectionController')]
    private string $name;

    #[ORM\Column(length: 255)]
    #[Groups('ProxmoxConnectionController')]
    private string $hostname;

    #[ORM\Column(length: 255)]
    #[Groups('ProxmoxConnectionController')]
    private string $ipAddress;

    #[ORM\Column(length: 255)]
    #[Groups('ProxmoxConnectionController')]
    private string $username;

    #[ORM\Column(length: 255)]
    #[Groups('ProxmoxConnectionController')]
    private string $password;

    #[ORM\Column(length: 255)]
    #[Groups('ProxmoxConnectionController')]
    private string $authType;

    #[ORM\Column(length: 255)]
    #[Groups('ProxmoxConnectionController')]
    private string $port;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getHostname(): string
    {
        return $this->hostname;
    }

    public function setHostname(string $hostname): void
    {
        $this->hostname = $hostname;
    }

    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(string $ipAddress): void
    {
        $this->ipAddress = $ipAddress;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getAuthType(): string
    {
        return $this->authType;
    }

    public function setAuthType(string $authType): void
    {
        $this->authType = $authType;
    }

    public function getPort(): string
    {
        return $this->port;
    }

    public function setPort(string $port): void
    {
        $this->port = $port;
    }

}