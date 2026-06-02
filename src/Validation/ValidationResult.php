<?php

declare(strict_types=1);

namespace App\Validation;

final class ValidationResult
{
    /**
     * @param list<string> $errors
     */
    public function __construct(
        private readonly bool $isValid,
        private readonly array $errors,
    ) {
    }

    public static function success(): self
    {
        return new self(true, []);
    }

    /**
     * @param list<string> $errors
     */
    public static function failure(array $errors): self
    {
        return new self(false, $errors);
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * @return list<string>
     */
    public function errors(): array
    {
        return $this->errors;
    }
}
