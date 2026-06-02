<?php

declare(strict_types=1);

namespace Tests\Validation;

use App\Validation\Rules\MaxDocumentSizeRule;
use App\Validation\Rules\ProhibitedWordsRule;
use App\Validation\TenantValidationRuleResolver;
use PHPUnit\Framework\TestCase;

final class TenantValidationRuleResolverTest extends TestCase
{
    public function testTenantWithNoConfiguredRulesReturnsEmptyRuleList(): void
    {
        $resolver = new TenantValidationRuleResolver([]);

        self::assertSame([], $resolver->resolve('unknown-tenant'));
    }

    public function testTenantSpecificRulesAreResolvedCorrectly(): void
    {
        $maxDocumentSizeRule = new MaxDocumentSizeRule(100);
        $prohibitedWordsRule = new ProhibitedWordsRule(['forbidden']);
        $resolver = new TenantValidationRuleResolver([
            'tenant-1' => [
                $maxDocumentSizeRule,
                $prohibitedWordsRule,
            ],
        ]);

        self::assertSame(
            [
                $maxDocumentSizeRule,
                $prohibitedWordsRule,
            ],
            $resolver->resolve('tenant-1'),
        );
    }
}
