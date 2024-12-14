<?php

namespace App\Controller;

use App\Dto\AuthorizationDto;
use App\Processor\UserProcessor;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;
use Symfony\Component\Security\Core\User\UserInterface;
use Webmozart\Assert\Assert;


#[Route('/api')]
final class UserController extends AbstractController
{
    public function __construct(
        private readonly UserProcessor $userProcessor
    ){
    }

    #[Route('/register', name: 'register', methods: ['POST'])]
    #[OA\Post(
        operationId: 'user-register',
        summary: 'Register',
        tags: ['Authorization'],

    )]
    #[OA\RequestBody(
        content: [
            new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(properties: [
                    new OA\Property(
                        property: 'email',
                        type: 'string',
                        example: 'm@m.pl'
                    ),
                    new OA\Property(
                        property: 'password',
                        type: 'string',
                        example: '123456'
                    ),
                ])
            ),
        ]
    )]
    public function register(#[MapRequestPayload] AuthorizationDto $dto): Response
    {
        try {
            return $this->userProcessor->createNewUser($dto);

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    #[Route( '/api/auth/user', name: 'edit-user-data', methods: ['PUT'])]
    #[Security(name: 'Bearer')]
    #[OA\Put(
        operationId: 'user-edit',
        summary: 'Edit',
        tags: ['UserConstraint'],

    )]
    #[OA\RequestBody(
        content: [
            new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(properties: [
                    new OA\Property(
                        property: 'email',
                        type: 'string',
                    ),
                    new OA\Property(
                        property: 'password',
                        type: 'string',
                    ),
                ])
            ),
        ]
    )]

    public function edit(Request $request, UserInterface $user)
    {
        $decoded = json_decode($request->getContent(), true);
        $email = $decoded['email'];
        Assert::email($email,'Given email address is not correct');

       return new JsonResponse( ["message" => 'Successfully update, new address email is: '.$email, 'code'=>200]);
    }
    #[Route('/api/auth/user/{id}', name: 'user-get', methods: ['GET'])]
    #[Security(name: 'Bearer')]
    #[OA\Get(
        operationId: 'user-get',
        summary: 'Get Self Information',
        tags: ['UserConstraint'],
    )]
    public function get()
    {
        return new JsonResponse( ["message" => $this->getUser()]);
    }
}
