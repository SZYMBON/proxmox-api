<?php

namespace App\Action;

use App\Dto\ProxmoxConnectionDto;
use App\Entity\ProxmoxConnection;
use App\Mapper\ProxmoxConnectionEntityMapperInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Webmozart\Assert\Assert;

final class ProxmoxConnectionEditAction
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ProxmoxConnectionEntityMapperInterface $proxmoxNodeEntityMapper
    ){

    }

    public function execute($data, string $id): JsonResponse
    {
        try {
            Assert::notNull($id, 'Node ID is required');

            $node = $this->entityManager->getRepository(ProxmoxConnection::class)->find($id);
            Assert::notNull($node, 'Node not found');

            if($data instanceof ProxmoxConnectionDto){
                $this->editNode($node, $data);
            }

            if($data instanceof Request){
                $this->patchNode($node, $data);
            }

            return new JsonResponse(["message" => "Proxmox API data successfully updated"]);

        } catch (\Exception $e) {
            return new JsonResponse(["message" => $e->getMessage()]);
        }
    }

    public function editNode(ProxmoxConnection $node, ProxmoxConnectionDto $proxmoxNodeDto): void
    {
        $this->proxmoxNodeEntityMapper->updateEntity($node, $proxmoxNodeDto);

        $this->entityManager->flush();
    }

    private function patchNode(ProxmoxConnection $node, Request $request): void
    {
        $data = json_decode($request->getContent(), true);
        foreach ($data as $key => $value) {
            $node->{'set' . ucfirst($key)}($value);
        }

        $this->entityManager->flush();

    }

}