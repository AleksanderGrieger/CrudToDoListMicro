<?php

namespace App\Infrastructure;

use App\Dto\TranslateRequest;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiClient implements ApiClientInterface
{
    public function __construct(
        private readonly HttpClientInterface $client
//        private readonly
    )
    {
    }
    public function sendRequest(TranslateRequest $request)
    {
        try {
            $response = $this->client->request(
                "POST",
                'https://api.funtranslations.com/translate/yoda',
                [
                    'body' => '{"text": "We are in great danger."}'
                ]
            );
        var_dump($response->getContent());exit;
        } catch (TransportExceptionInterface $e) {
            throw new TransportException($e->getMessage());
        }



    }
}