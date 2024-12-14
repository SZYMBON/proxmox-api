<?php

namespace App\Processor;

use App\Dto\AuthorizationDto;
use App\Entity\User;
use App\Validator\UserValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserProcessor
{
    public function __construct(
       private readonly UserPasswordHasherInterface $passwordEncoder,
       private readonly EntityManagerInterface $entityManager,
    ){
    }

    public function createNewUser(AuthorizationDto $dto): JsonResponse
    {
        $user = new User();
        $user->setEmail($dto->email);
        $password = $this->hashPassword($dto->password,$user);
        $user->setPassword($password);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse( ["message" => "Registration successfully"]);
    }

    public function hashPassword(string $password, User $user): string
    {
        $pass = $this->passwordEncoder->hashPassword($user, $password);
        return $pass;
    }
}