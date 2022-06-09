<?php

namespace App\Infrastructure;

use App\Dto\TranslateRequest;

interface ApiClientInterface
{
    public function sendRequest(TranslateRequest $request);
}