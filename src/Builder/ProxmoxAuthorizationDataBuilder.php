<?php

namespace App\Builder;

use App\Entity\ProxmoxConnection;
use App\Enum\ProxmoxApiEndpointsEnum;
use App\Factory\ProxmoxConnectionFactory;

class ProxmoxAuthorizationDataBuilder
{
    public function __construct(
        private readonly ProxmoxConnectionFactory $proxmoxConnectionFactory,
        private readonly ProxmoxRequestBuilder $proxmoxRequestFactory
    )
    {
    }

    public function getAuthorizationRequest( string $connectionId ): array
    {
        $connectionDetails = $this->proxmoxConnectionFactory->getConnectionDetailsByNodeId($connectionId);
        $authorizationData = $this->setAuthorizationData($connectionDetails);

        return $this->proxmoxRequestFactory->createRequest(
            $connectionId,
            $authorizationData,
            ProxmoxApiEndpointsEnum::TICKET->value
        );
    }

    public function getAuthorizationData(string $ticket, string $csrfToken): array
    {
        return [
            'headers' =>[
                'Cookie' => sprintf('PVEAuthCookie=%s', $ticket),
                'CSRFPreventionToken' => $csrfToken
            ]
        ];
    }

    private function setAuthorizationData(ProxmoxConnection $authorizationData): array
    {
        return [
            'json' => [
                'username' => $authorizationData->getUsername(),
                'password' => $authorizationData->getPassword(),
                'realm' => $authorizationData->getAuthType()
            ]
        ];
    }

}