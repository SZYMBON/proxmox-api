<?php

namespace App\Controller;

use App\Action\ProxmoxConnectionCreateAction;
use App\Action\ProxmoxConnectionDeleteAction;
use App\Action\ProxmoxConnectionEditAction;
use App\Action\ProxmoxConnectionGetAction;
use App\Dto\ProxmoxConnectionDto;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/admin/proxmox/connection')]
#[Security(name: 'Bearer')]
final class ProxmoxConnectionController extends AbstractController
{
    public function __construct(
        private readonly ProxmoxConnectionCreateAction $proxmoxNodeCreateAction,
        private readonly ProxmoxConnectionEditAction $proxmoxNodeEditAction,
        private readonly ProxmoxConnectionGetAction $proxmoxNodeGetAction,
        private readonly ProxmoxConnectionDeleteAction $proxmoxNodeDeleteAction,
        private readonly SerializerInterface $serializer
    ){
    }

    #[Route( '', name: 'create-proxmox-connection', methods: ['POST'])]
    #[OA\Post(
        operationId: 'create-proxmox-connection',
        summary: 'Creates a new proxmox connection to node',
        tags: ['Proxmox Connection Configuration'],

    )]
    #[OA\RequestBody(
        content: [
            new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(properties: [
                    new OA\Property(
                        property: 'name',
                        type: 'string',
                        example: 'new proxmox connection'
                    ),
                    new OA\Property(
                        property: 'hostname',
                        type: 'string',
                        example: 'localhost'
                    ),
                    new OA\Property(
                        property: 'ipAddress',
                        type: 'string',
                        example: '127.0.0.1'
                    ),
                    new OA\Property(
                        property: 'username',
                        type: 'string',
                        example: 'root'
                    ),
                    new OA\Property(
                        property: 'password',
                        type: 'string',
                        example: 'password'
                    ),
                    new OA\Property(
                        property: 'authType',
                        type: 'string',
                        example: 'pwd'
                    ),
                    new OA\Property(
                        property: 'port',
                        type: 'string',
                        example: '80'
                    ),
                    new OA\Property(
                        property: 'active',
                        type: 'bool',
                        example: true
                    ),
                    new OA\Property(
                        property: 'isDefault',
                        type: 'bool',
                        example: true
                    ),
                ])
            ),
        ]
    )]
    public function store(#[MapRequestPayload] ProxmoxConnectionDto $proxmoxApiDataDto): Response
    {
        return $this->proxmoxNodeCreateAction->execute($proxmoxApiDataDto);
    }

    #[Route( '', name: 'get-all-proxmox-connections', methods: ['GET'])]
    #[OA\Get(
        operationId: 'get-all-proxmox-connections',
        summary: 'Get all proxmox connections',
        tags: ['Proxmox Connection Configuration'],
    )]

    public function getAll():Response
    {
        try {
            $nodes = $this->proxmoxNodeGetAction->execute(null);
            return new JsonResponse($this->serializer->serialize($nodes, 'json'), Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route( '/{connection}', name: 'get-proxmox-connections', methods: ['GET'])]
    #[OA\Get(
        operationId: 'get-proxmox-connections',
        summary: 'Get single proxmox connection',
        tags: ['Proxmox Connection Configuration'],
    )]

    public function get(Request $request):Response
    {
        try {
            $node = $this->proxmoxNodeGetAction->execute($request->get('connection'));

            return new JsonResponse($this->serializer->serialize($node, 'json'), Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route( '/{connection}', name: 'edit-proxmox-connection', methods: ['PUT'])]
    #[OA\Put(
        operationId: 'edit-proxmox-connection',
        summary: 'Edit proxmox connection',
        tags: ['Proxmox Connection Configuration'],

    )]
    #[OA\RequestBody(
        content: [
            new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(properties: [
                    new OA\Property(
                        property: 'name',
                        type: 'string',
                        example: 'proxmox connection'
                    ),
                    new OA\Property(
                        property: 'hostname',
                        type: 'string',
                        example: 'myhost.local'
                    ),
                    new OA\Property(
                        property: 'ipAddress',
                        type: 'string',
                        example: '123.123.123.123'
                    ),
                    new OA\Property(
                        property: 'username',
                        type: 'string',
                        example: 'root'
                    ),
                    new OA\Property(
                        property: 'password',
                        type: 'string',
                        example: 'password'
                    ),
                    new OA\Property(
                        property: 'authType',
                        type: 'string',
                        example: 'pwd'
                    ),
                    new OA\Property(
                        property: 'port',
                        type: 'string',
                        example: '8006'
                    ),
                    new OA\Property(
                        property: 'active',
                        type: 'bool',
                        example: true
                    ),
                    new OA\Property(
                        property: 'isDefault',
                        type: 'bool',
                        example: true
                    ),
                ])
            ),
        ]
    )]
    public function edit(#[MapRequestPayload] ProxmoxConnectionDto $proxmoxApiDataDto, Request $request): Response
    {
        return $this->proxmoxNodeEditAction->execute($proxmoxApiDataDto, $request->get('connection'));
    }

    #[Route( '/{connection}', name: 'partially-edit-proxmox-connection', methods: ['PATCH'])]
    #[OA\Patch(
        operationId: 'partially-edit-proxmox-connection',
        summary: 'Edit a part of proxmox connection',
        tags: ['Proxmox Connection Configuration'],

    )]
    #[OA\RequestBody(
        content: [
            new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(properties: [
                    new OA\Property(
                        property: 'active',
                        type: 'bool',
                        example: false
                    ),
                    new OA\Property(
                        property: 'isDefault',
                        type: 'bool',
                        example: true
                    ),
                ])
            ),
        ]
    )]
    public function patch(Request $request): Response
    {
        return $this->proxmoxNodeEditAction->execute($request, $request->get('connection'));
    }

    #[Route( '/{connection}', name: 'delete-proxmox-connection', methods: ['DELETE'])]
    #[OA\Delete(
        operationId: 'delete-proxmox-connection',
        summary: 'Edit a part of proxmox connection',
        tags: ['Proxmox Connection Configuration'],

    )]
    public function delete(Request $request): Response
    {
        return $this->proxmoxNodeDeleteAction->execute($request->get('connection'));
    }
}