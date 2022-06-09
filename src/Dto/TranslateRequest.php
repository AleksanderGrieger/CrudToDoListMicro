<?php

namespace App\Dto;

use OpenApi\Attributes as OA;
use JMS\Serializer\Annotation as JMS;

#[JMS\AccessType(type: "public_method")]
class TranslateRequest
{
    #[JMS\Type('string')]
    #[JMS\SerializedName(name: 'text')]
    #[OA\Property(type: "string", example: "We are in great danger.")]
    private string $text;

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }
}