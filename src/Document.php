<?php

declare(strict_types=1);

namespace App;

final class Document
{
    /**
     * @param array<string, mixed> $metadata
     */
    public function __construct(
        private readonly string $id,
        private readonly string $tenantId,
        private readonly string $content,
        private readonly array $metadata,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function tenantId(): string
    {
        return $this->tenantId;
    }

    public function content(): string
    {
        return $this->content;
    }

    /**
     * @return array<string, mixed>
     */
    public function metadata(): array
    {
        return $this->metadata;
    }
}
