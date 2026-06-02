<?php

declare(strict_types=1);

namespace Tests\Validation;

use App\Document;
use App\Validation\DocumentValidator;
use App\Validation\Rules\MaxDocumentSizeRule;
use App\Validation\Rules\ProhibitedWordsRule;
use App\Validation\Rules\RequiredMetadataFieldsRule;
use PHPUnit\Framework\TestCase;

final class DocumentValidatorTest extends TestCase
{
    public function testValidDocumentPassesValidation(): void
    {
        $validator = new DocumentValidator([
            new MaxDocumentSizeRule(100),
            new RequiredMetadataFieldsRule(['author']),
            new ProhibitedWordsRule(['forbidden']),
        ]);
        $document = new Document('document-1', 'tenant-1', 'Allowed content.', ['author' => 'Alice']);

        $result = $validator->validate($document);

        self::assertTrue($result->isValid());
        self::assertSame([], $result->errors());
    }

    public function testMaxDocumentSizeValidationFails(): void
    {
        $validator = new DocumentValidator([
            new MaxDocumentSizeRule(5),
        ]);
        $document = new Document('document-1', 'tenant-1', 'Too long', []);

        $result = $validator->validate($document);

        self::assertFalse($result->isValid());
        self::assertSame(
            ['Document content exceeds the maximum size of 5 bytes.'],
            $result->errors(),
        );
    }

    public function testRequiredMetadataValidationFails(): void
    {
        $validator = new DocumentValidator([
            new RequiredMetadataFieldsRule(['author']),
        ]);
        $document = new Document('document-1', 'tenant-1', 'Content.', []);

        $result = $validator->validate($document);

        self::assertFalse($result->isValid());
        self::assertSame(
            ['Required metadata field "author" is missing.'],
            $result->errors(),
        );
    }

    public function testNullMetadataValueIsTreatedAsMissing(): void
    {
        $validator = new DocumentValidator([
            new RequiredMetadataFieldsRule(['author']),
        ]);
        $document = new Document('document-1', 'tenant-1', 'Content.', ['author' => null]);

        $result = $validator->validate($document);

        self::assertFalse($result->isValid());
        self::assertSame(
            ['Required metadata field "author" is missing.'],
            $result->errors(),
        );
    }

    public function testProhibitedWordValidationFails(): void
    {
        $validator = new DocumentValidator([
            new ProhibitedWordsRule(['forbidden']),
        ]);
        $document = new Document('document-1', 'tenant-1', 'Contains FORBIDDEN content.', []);

        $result = $validator->validate($document);

        self::assertFalse($result->isValid());
        self::assertSame(
            ['Document content contains prohibited word "forbidden".'],
            $result->errors(),
        );
    }

    public function testMultipleValidationErrorsAreCollected(): void
    {
        $validator = new DocumentValidator([
            new MaxDocumentSizeRule(5),
            new RequiredMetadataFieldsRule(['author', 'category']),
            new ProhibitedWordsRule(['forbidden']),
        ]);
        $document = new Document('document-1', 'tenant-1', 'Forbidden content.', []);

        $result = $validator->validate($document);

        self::assertFalse($result->isValid());
        self::assertSame(
            [
                'Document content exceeds the maximum size of 5 bytes.',
                'Required metadata field "author" is missing.',
                'Required metadata field "category" is missing.',
                'Document content contains prohibited word "forbidden".',
            ],
            $result->errors(),
        );
    }
}
