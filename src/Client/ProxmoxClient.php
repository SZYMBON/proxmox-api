<?php

namespace App\Client;

use App\Builder\ProxmoxAuthorizationDataBuilder;
use App\Builder\ProxmoxRequestBuilder;
use App\Dto\LinuxContainerDto;
use App\Entity\ProxmoxConnection;
use App\Enum\ProxmoxApiEndpointsEnum;
use App\Factory\ProxmoxConnectionFactory;
use App\Router\Router;
use Symfony\Component\HttpFoundation\Request;
use Webmozart\Assert\Assert;

final class ProxmoxClient
{
    public function __construct(
        private readonly ProxmoxAuthorizationDataBuilder $proxmoxAuthorizationDataBuilder,
        private readonly ProxmoxRequestBuilder $proxmoxRequestBuilder,
        private readonly Router $router
    )
    {
    }

    public function getNodesByConnectionId(string $connectionId): array
    {
        $requestData = $this->proxmoxAuthorizationDataBuilder->getAuthorizationData(
            $this->getTicketForConnection($connectionId),
            $this->getCsrfToken($connectionId)
        );


        $request = $this->proxmoxRequestBuilder->createRequest($connectionId, $requestData, ProxmoxApiEndpointsEnum::NODES->value);
        $response = $this->router->get($request);
        return json_decode($response->getContent(),true);
    }

    public function createLinuxContainer(Request $request): array
    {
        $connectionId = $request->get('connection');
        $node = $request->get('node');

        $requestData = $this->proxmoxAuthorizationDataBuilder->getAuthorizationData(
            $this->getTicketForConnection($connectionId),
            $this->getCsrfToken($connectionId)
        );

        $requestData['json'] = json_decode($request->getContent(), true);

        $request = $this->proxmoxRequestBuilder->createRequest($connectionId, $requestData,
            sprintf(ProxmoxApiEndpointsEnum::CONTAINERS->value, $node)
        );

        $response = $this->router->post($request);
        return  json_decode($response->getContent(),true);
    }

    public function getLinuxContainers(Request $request): array
    {
        $connectionId = $request->get('connection');
        $node = $request->get('node');

        $requestData = $this->proxmoxAuthorizationDataBuilder->getAuthorizationData(
            $this->getTicketForConnection($connectionId),
            $this->getCsrfToken($connectionId)
        );

        $request = $this->proxmoxRequestBuilder->createRequest($connectionId, $requestData,
            sprintf(ProxmoxApiEndpointsEnum::CONTAINERS->value, $node)
        );
        $response = $this->router->get($request);
        return json_decode($response->getContent(),true);
    }

    public function deleteLinuxContainer(Request $request): array
    {
        $connectionId = $request->get('connection');
        $node = $request->get('node');
        $container = $request->get('container');

        $requestData = $this->proxmoxAuthorizationDataBuilder->getAuthorizationData(
            $this->getTicketForConnection($connectionId),
            $this->getCsrfToken($connectionId)
        );

        $request = $this->proxmoxRequestBuilder->createRequest($connectionId, $requestData,
            sprintf(ProxmoxApiEndpointsEnum::CONTAINER->value, $node, $container)
        );
        $response = $this->router->delete($request);
        return json_decode($response->getContent(),true);
    }

    

    private function authorize( string $connectionId ): array
    {
        $requestData = $this->proxmoxAuthorizationDataBuilder->getAuthorizationRequest($connectionId);
        $request = $this->router->post($requestData);
        $response = json_decode($request->getContent(), true);
        Assert::notNull($response['data']['ticket'], 'Ticket not found');
        return $response['data'];
    }

    private function getTicketForConnection(string $connectionId): string
    {
        $response = $this->authorize($connectionId);
        return $response['ticket'];
    }

    private function getCsrfToken( string $connectionId ): string
    {
        $response = $this->authorize($connectionId);
        return $response['CSRFPreventionToken'];
    }

}