<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use App\Document;
use App\Validation\ValidationRuleInterface;

final class MaxDocumentSizeRule implements ValidationRuleInterface
{
    public function __construct(private readonly int $maximumSizeInBytes)
    {
    }

    public function validate(Document $document): array
    {
        if (strlen($document->content()) <= $this->maximumSizeInBytes) {
            return [];
        }

        return [
            sprintf(
                'Document content exceeds the maximum size of %d bytes.',
                $this->maximumSizeInBytes,
            ),
        ];
    }
}
