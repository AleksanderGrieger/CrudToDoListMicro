<?php

namespace App\Dto;

use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\Type;

/** @AccessType("public_method") */
class TaskRequest
{
    #[Type('string')]
    private $title;

    #[Type('bool')]
    private $status;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;

        return $this;
    }
}