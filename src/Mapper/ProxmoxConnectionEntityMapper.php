<?php

namespace App\Mapper;

use App\Dto\ProxmoxConnectionDto;
use App\Entity\ProxmoxConnection;

final class ProxmoxConnectionEntityMapper implements ProxmoxConnectionEntityMapperInterface
{
    public function mapToEntity(ProxmoxConnectionDto $dto): ProxmoxConnection
    {
        $node = new ProxmoxConnection();
        $this->mapFields($node, $dto);
        return $node;
    }

    public function updateEntity(ProxmoxConnection $node, ProxmoxConnectionDto $dto): ProxmoxConnection
    {
        $this->mapFields($node, $dto);
        return $node;
    }

    private function mapFields(ProxmoxConnection $node, ProxmoxConnectionDto $dto): ProxmoxConnection
    {
        $node->setName($dto->name);
        $node->setHostname($dto->hostname);
        $node->setIpAddress($dto->ipAddress);
        $node->setUsername($dto->username);
        $node->setPassword($dto->password);
        $node->setPort($dto->port);
        $node->setAuthType($dto->authType);

        return $node;
    }
}