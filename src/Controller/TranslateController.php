<?php

namespace App\Controller;

use App\Dto\TranslateRequest;
use App\Dto\TranslateResponse;
use App\Service\TranslateService;
use AutoMapperPlus\AutoMapperInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;

#[Rest\Route('/api/translate')]
#[OA\Tag(name: 'Translate')]
class TranslateController
{
    public function __construct(
        private readonly AutoMapperInterface $autoMapper,
        private readonly TranslateService $service
    )
    {
    }

    #[Rest\Post('/yoda', name: 'post_translate_fraze')]
    #[OA\RequestBody( required: true, content: new OA\JsonContent(
        ref: new Model(type: TranslateRequest::class),
        type: 'object'
    ))]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Translates to Yoda',
        content: new OA\JsonContent(
            ref: new Model(type: TranslateResponse::class),
            type: 'object'
        )
    )]
    public function postTranslateFraze(Request $request): View
    {
        $body = $this->autoMapper->map(json_decode($request->getContent()), TranslateRequest::class);

        $responseApi = $this->service->translateFraze($body);
        var_dump($responseApi);exit;

        $response = $this->autoMapper->map($responseApi, TranslateResponse::class);

        return  View::create($response, Response::HTTP_OK);
    }
}