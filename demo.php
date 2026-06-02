<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use App\Document;
use App\Validation\DocumentValidator;
use App\Validation\Rules\MaxDocumentSizeRule;
use App\Validation\Rules\ProhibitedWordsRule;
use App\Validation\Rules\RequiredMetadataFieldsRule;
use App\Validation\TenantValidationRuleResolver;

$ruleResolver = new TenantValidationRuleResolver([
    'tenant-a' => [
        new MaxDocumentSizeRule(100),
        new RequiredMetadataFieldsRule(['author']),
    ],
    'tenant-b' => [
        new RequiredMetadataFieldsRule(['category']),
        new ProhibitedWordsRule(['confidential']),
    ],
]);

$documents = [
    new Document(
        'document-1',
        'tenant-a',
        'A valid document.',
        ['author' => 'Alice'],
    ),
    new Document(
        'document-2',
        'tenant-b',
        'This document contains confidential information.',
        [],
    ),
];

foreach ($documents as $document) {
    $validator = new DocumentValidator($ruleResolver->resolve($document->tenantId()));
    $result = $validator->validate($document);

    echo sprintf("Document ID: %s\n", $document->id());
    echo sprintf("Tenant ID: %s\n", $document->tenantId());
    echo sprintf("Status: %s\n", $result->isValid() ? 'VALID' : 'INVALID');
    echo "Errors:\n";

    if ($result->errors() === []) {
        echo "- none\n";
    } else {
        foreach ($result->errors() as $error) {
            echo sprintf("- %s\n", $error);
        }
    }

    echo "\n";
}
