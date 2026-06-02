<?php

declare(strict_types=1);

namespace App\Validation;

use App\Document;

final class DocumentValidator
{
    /**
     * @param list<ValidationRuleInterface> $rules
     */
    public function __construct(private readonly array $rules)
    {
    }

    public function validate(Document $document): ValidationResult
    {
        $errors = [];

        foreach ($this->rules as $rule) {
            foreach ($rule->validate($document) as $error) {
                $errors[] = $error;
            }
        }

        if ($errors === []) {
            return ValidationResult::success();
        }

        return ValidationResult::failure($errors);
    }
}
