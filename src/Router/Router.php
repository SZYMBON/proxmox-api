<?php

namespace App\Router;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Webmozart\Assert\Assert;

final class Router
{
    private const POST = 'POST';
    private const GET = 'GET';

    private const DELETE = 'DELETE';

    public function __construct(
        private readonly HttpClientInterface $httpClient
    )
    {
    }

    public function post(array $requestData): ResponseInterface
    {
        $this->checkRequestData($requestData);
        return $this->sendRequest($requestData, self::POST);
    }

    public function get(array $requestData): ResponseInterface
    {
        $this->checkRequestData($requestData);
        return $this->sendRequest($requestData, self::GET);
    }

    public function delete(array $requestData): ResponseInterface
    {
        $this->checkRequestData($requestData);
        return $this->sendRequest($requestData, self::DELETE);
    }

    private function sendRequest(array $requestData, string $method): ResponseInterface
    {
        return $this->httpClient->request(
            $method,
            $requestData['url'],
            $requestData['data']
        );
    }

    private function checkRequestData( array $requestData): void
    {
        Assert::notNull($requestData['url'], 'Url is required');
        Assert::notNull($requestData['data'], 'Data is required');
    }
}