<?php

namespace App\Factory;

use App\Action\ProxmoxConnectionGetAction;
use App\Entity\ProxmoxConnection;
use Webmozart\Assert\Assert;

class ProxmoxConnectionFactory
{
    public function __construct( private readonly ProxmoxConnectionGetAction $proxmoxConnectionGetAction)
    {
    }

    public function getConnectionDetailsByNodeId(string $proxmoxConnection): ProxmoxConnection
    {
        $connection = $this->proxmoxConnectionGetAction->execute($proxmoxConnection);
        Assert::notNull($connection, 'Connection not found');
        return $connection;
    }
}