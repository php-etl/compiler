<?php

declare(strict_types=1);

namespace Kiboko\Component\StringExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;

class ConvertCharCode extends ExpressionFunction
{
    public function __construct($name)
    {
        parent::__construct(
            $name,
            $this->compile(...)->bindTo($this),
            $this->evaluate(...)->bindTo($this)
        );
    }

    private function compile(string $text, string $sourceCharCode, string $destinationCharCode): string
    {
        return <<<"PHP"
            iconv({$sourceCharCode}, {$destinationCharCode}, {$text})
            PHP;
    }

    private function evaluate(array $context, string $text, string $sourceCharCode, string $destinationCharCode)
    {
        return iconv($sourceCharCode, $destinationCharCode, $text);
    }
}
