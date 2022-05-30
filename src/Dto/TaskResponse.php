<?php

namespace App\Dto;

use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\Type;

/** @AccessType("public_method") */
class TaskResponse
{
    #[Type('integer')]
    private $id;

    #[Type('string')]
    private $title;

    #[Type('bool')]
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

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