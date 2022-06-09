<?php

namespace App\Dto;

use JMS\Serializer\Annotation as JMS;

#[JMS\AccessType(type: "public_method")]
class TranslateResponse
{
    #[JMS\Type('string')]
    #[JMS\SerializedName(name: 'translated')]
    private string $translated;

    #[JMS\Type('string')]
    #[JMS\SerializedName(name: 'translation')]
    private string $translation;

    /**
     * @return string
     */
    public function getTranslated(): string
    {
        return $this->translated;
    }

    /**
     * @param string $translated
     */
    public function setTranslated(string $translated): void
    {
        $this->translated = $translated;
    }

    /**
     * @return string
     */
    public function getTranslation(): string
    {
        return $this->translation;
    }

    /**
     * @param string $translation
     */
    public function setTranslation(string $translation): void
    {
        $this->translation = $translation;
    }
}