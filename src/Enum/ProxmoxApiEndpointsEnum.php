<?php

namespace App\Enum;

enum ProxmoxApiEndpointsEnum: string
{
    case TICKET = 'access/ticket';
    case NODES = 'nodes';
    case CONTAINERS = 'nodes/%s/lxc';
    case CONTAINER = 'nodes/%s/lxc/%s';
}
