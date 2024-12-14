<?php

namespace App\Controller;

use App\Client\ProxmoxClient;
use App\Dto\LinuxContainerDto;
use App\Dto\ProxmoxConnectionDto;
use http\Env\Response;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/admin/proxmox/connection/{connection}/node/{node}/containers')]
#[Security(name: 'Bearer')]
final class ProxmoxLinuxContainerController extends AbstractController
{
    public function __construct(
        private readonly ProxmoxClient $proxmoxClient,
    )
    {
    }

    #[Route( '', name: 'create-container', methods: ['POST'])]
    #[OA\Post(
        operationId: 'create-container',
        summary: 'Creates a new proxmox CT',
        tags: ['Proxmox Containers'],

    )]
    #[OA\RequestBody(
        content: [
            new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(properties: [
                    new OA\Property(
                        property: 'vmid',
                        type: 'integer',
                        example: 100
                    ),
                    new OA\Property(
                        property: 'cores',
                        type: 'integer',
                        example: 1
                    ),
                    new OA\Property(
                        property: 'cpulimit',
                        type: 'integer',
                        example: 1
                    ),
                    new OA\Property(
                        property: 'description',
                        type: 'string',
                        example: "My new container"
                    ),
                    new OA\Property(
                        property: 'hostname',
                        type: 'string',
                        example: "test.local"
                    ),
                    new OA\Property(
                        property: 'memory',
                        type: 'integer',
                        example: 512
                    ),
                    new OA\Property(
                        property: 'nameserver',
                        type: 'string',
                        example: "8.8.8.8"
                    ),
                    new OA\Property(
                        property: 'net0',
                        type: 'string',
                        example: "name=eth0,bridge=vmbr0,firewall=1,gw=255.255.255.255"
                    ),
                    new OA\Property(
                        property: 'ostemplate',
                        type: 'string',
                        example: "local:vztmpl/ubuntu-24.10-standard_24.10-1_amd64.tar.zst"
                    ),
                    new OA\Property(
                        property: 'password',
                        type: 'string',
                        example: "password"
                    ),
                    new OA\Property(
                        property: 'storage',
                        type: 'string',
                        example: "local-lvm"
                    ),
                    new OA\Property(
                        property: 'swap',
                        type: 'integer',
                        example: 512
                    ),
                ])
            ),
        ]
    )]
    public function store(Request $request): JsonResponse
    {
        try{
            $container = $this->proxmoxClient->createLinuxContainer($request);
            return new JsonResponse($container);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    #[Route( '', name: 'get-containers', methods: ['GET'])]
    #[OA\Get(
        operationId: 'get-containers',
        summary: 'Get all proxmox containers',
        tags: ['Proxmox Containers'],

    )]

    public function get(Request $request):JsonResponse
    {
        try{
            $containers = $this->proxmoxClient->getLinuxContainers($request);
            return new JsonResponse($containers);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
    #[Route( '/{container}', name: 'delete-linux-container', methods: ['DELETE'])]
    #[OA\Delete(
        operationId: 'delete-linux-container',
        summary: 'Delete proxmox linux container',
        tags: ['Proxmox Containers'],

    )]
    public function delete(Request $request):JsonResponse
    {
        try{
            $container = $this->proxmoxClient->deleteLinuxContainer($request);
            return new JsonResponse($container);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}