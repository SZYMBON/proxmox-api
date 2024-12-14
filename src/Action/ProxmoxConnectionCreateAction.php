<?php

namespace App\Action;

use App\Dto\ProxmoxConnectionDto;
use App\Mapper\ProxmoxConnectionEntityMapperInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

final class ProxmoxConnectionCreateAction
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ProxmoxConnectionEntityMapperInterface $proxmoxNodeEntityMapper
    ){

    }

    public function execute(ProxmoxConnectionDto $proxmoxApiDataDto): JsonResponse
    {
        try{
            $node = $this->proxmoxNodeEntityMapper->mapToEntity($proxmoxApiDataDto);

            $this->entityManager->persist($node);
            $this->entityManager->flush();

            return new JsonResponse(["message" => "Proxmox API data successfully saved"]);
        }catch (\Exception $e){
            return new JsonResponse(["message" => $e->getMessage()]);
        }
    }

}