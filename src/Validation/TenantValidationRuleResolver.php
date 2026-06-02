<?php

declare(strict_types=1);

namespace App\Validation;

final class TenantValidationRuleResolver
{
    /**
     * @param array<string, list<ValidationRuleInterface>> $tenantRules
     */
    public function __construct(private readonly array $tenantRules)
    {
    }

    /**
     * @return list<ValidationRuleInterface>
     */
    public function resolve(string $tenantId): array
    {
        return $this->tenantRules[$tenantId] ?? [];
    }
}
