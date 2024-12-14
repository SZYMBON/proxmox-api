<?php

namespace App\Controller;

use App\Client\ProxmoxClient;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

#[Route('/api/admin/proxmox/connection/{connection}/node')]
#[Security(name: 'Bearer')]
class ProxmoxNodeController extends AbstractController
{
    public function __construct(
        private readonly ProxmoxClient $proxmoxClient,
    )
    {
    }

    #[Route( '', name: 'get-proxmox-nodes', methods: ['GET'])]
    #[OA\Get(
        operationId: 'get-proxmox-nodes',
        summary: 'Get all proxmox nodes',
        tags: ['Proxmox Nodes'],

    )]
    public function getNodes(Request $request): JsonResponse
    {
        try{
            $connectionId = $request->get('connection');
            $nodes = $this->proxmoxClient->getNodesByConnectionId($connectionId);
            return new JsonResponse($nodes);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}