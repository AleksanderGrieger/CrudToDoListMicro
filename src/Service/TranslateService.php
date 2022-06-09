<?php

namespace App\Service;

use App\Dto\TranslateRequest;
use App\Infrastructure\ApiClientInterface;

class TranslateService
{
    public function __construct(
        private readonly ApiClientInterface $apiClient
    )
    {
    }

    public function translateFraze(TranslateRequest $request)
    {
        var_dump($request);exit;
        return $this->apiClient->sendRequest($request);
    }
}