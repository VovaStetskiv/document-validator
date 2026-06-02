<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use App\Document;
use App\Validation\ValidationRuleInterface;

final class ProhibitedWordsRule implements ValidationRuleInterface
{
    /**
     * @param list<string> $prohibitedWords
     */
    public function __construct(private readonly array $prohibitedWords)
    {
    }

    public function validate(Document $document): array
    {
        $errors = [];

        foreach ($this->prohibitedWords as $word) {
            if ($word === '') {
                continue;
            }

            if (stripos($document->content(), $word) !== false) {
                $errors[] = sprintf('Document content contains prohibited word "%s".', $word);
            }
        }

        return $errors;
    }
}
