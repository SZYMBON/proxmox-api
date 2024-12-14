<?php

namespace App\Builder;

use App\Enum\ProxmoxApiEndpointsEnum;
use App\Factory\ProxmoxConnectionFactory;
use Webmozart\Assert\Assert;

class ProxmoxRequestBuilder
{
    public function __construct(
        private readonly ProxmoxConnectionFactory $connectionFactory,
    ){

    }

    public function createRequest( string $connectionId, array $requestData, string $endpoint ): array
    {
        Assert::notNull($requestData, 'Request data is not set');
        Assert::notNull($connectionId, 'Connection id is not set');
        return [
            'url' => sprintf('%s%s', $this->getBaseUrlByConnectionId($connectionId), $endpoint),
            'data' => [
                'query' => $requestData['query'] ?? null,
                'headers' => $requestData['headers'] ?? null,
                'json' => $requestData['json'] ?? null,
            ]
        ];
    }

    private function getBaseUrlByConnectionId( string $connectionId ): string
    {
        $connection = $this->connectionFactory->getConnectionDetailsByNodeId($connectionId);
        return sprintf( "%s%s%s%s%s",
            'https://',
            $connection->getIpAddress(),
            ':',
            $connection->getPort(),
            "/api2/json/"
        );
    }

}