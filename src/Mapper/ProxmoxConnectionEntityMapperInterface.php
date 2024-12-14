<?php

namespace App\Mapper;

use App\Dto\ProxmoxConnectionDto;
use App\Entity\ProxmoxConnection;

interface ProxmoxConnectionEntityMapperInterface
{
    public function mapToEntity(ProxmoxConnectionDto $dto): ProxmoxConnection;

    public function updateEntity(ProxmoxConnection $node, ProxmoxConnectionDto $dto): ProxmoxConnection;

}