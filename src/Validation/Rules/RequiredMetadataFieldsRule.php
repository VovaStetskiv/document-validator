<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use App\Document;
use App\Validation\ValidationRuleInterface;

final class RequiredMetadataFieldsRule implements ValidationRuleInterface
{
    /**
     * @param list<string> $requiredFields
     */
    public function __construct(private readonly array $requiredFields)
    {
    }

    public function validate(Document $document): array
    {
        $errors = [];
        $metadata = $document->metadata();

        foreach ($this->requiredFields as $field) {
            if (!isset($metadata[$field])) {
                $errors[] = sprintf('Required metadata field "%s" is missing.', $field);
            }
        }

        return $errors;
    }
}
