<?php

declare(strict_types=1);

namespace Kiboko\Component\StringExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;

final class AsInteger extends ExpressionFunction
{
    public function __construct($name)
    {
        parent::__construct(
            $name,
            $this->compile(...)->bindTo($this),
            $this->evaluate(...)->bindTo($this)
        );
    }

    private function compile(string $value): string
    {
        return <<<PHP
                ((int) {$value})
            PHP;
    }

    private function evaluate(array $context, string $value)
    {
        return (int) $value;
    }
}
