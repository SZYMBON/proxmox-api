<?php

namespace App\Action;

use App\Entity\ProxmoxConnection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Webmozart\Assert\Assert;

final class ProxmoxConnectionDeleteAction
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ){

    }

    public function execute(string $id): JsonResponse
    {
        try {
            $node = $this->entityManager->getRepository(ProxmoxConnection::class)->find($id);
            Assert::notNull($node, 'Node not found');

            $this->entityManager->remove($node);
            $this->entityManager->flush();

            return new JsonResponse(["message" => "Proxmox API data successfully deleted"]);
        }catch (\Exception $e){
            return new JsonResponse(["message" => $e->getMessage()]);
        }
    }

}