<?php

declare(strict_types=1);

namespace Kiboko\Component\StringExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;

final class CapitalizeWords extends ExpressionFunction
{
    public function __construct($name)
    {
        parent::__construct(
            $name,
            \Closure::fromCallable($this->compile(...))->bindTo($this),
            \Closure::fromCallable($this->evaluate(...))->bindTo($this)
        );
    }

    private function compile(string $input): string
    {
        return <<<PHP
            (
                !\\is_string({$input}) ?
                null :
                (
                    mb_convert_case({$input}, \\MB_CASE_TITLE)
                )
            )
            PHP;
    }

    private function evaluate(array $context, string $input)
    {
        return !\is_string($input) ?
            null :
            (
                mb_convert_case($input, \MB_CASE_TITLE)
            );
    }
}
