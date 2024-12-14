<?php

namespace App\Action;

use App\Entity\ProxmoxConnection;
use Doctrine\ORM\EntityManagerInterface;
use Webmozart\Assert\Assert;

final class ProxmoxConnectionGetAction
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ){

    }

    public function execute(?string $id): ProxmoxConnection|array
    {
        if (isset($id)) {
            return $this->getNodeById($id);
        }
        return $this->getAllNodes();

    }

    private function getNodeById(string $id): ProxmoxConnection
    {
        Assert::notNull($id, 'Node ID is required');

        $node = $this->entityManager->getRepository(ProxmoxConnection::class)->find($id);
        Assert::notNull($node, 'Node not found');

        return $node;
    }

    private function getAllNodes(): array
    {
        $nodes = $this->entityManager->getRepository(ProxmoxConnection::class)->findAll();
        Assert::notEmpty($nodes, 'No nodes found');

       return $nodes;
    }

}