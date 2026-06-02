<?php

declare(strict_types=1);

namespace App\Validation;

use App\Document;

interface ValidationRuleInterface
{
    /**
     * @return list<string>
     */
    public function validate(Document $document): array;
}
