<?php

declare(strict_types=1);

namespace Kiboko\Component\StringExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;

final class TruncateFileName extends ExpressionFunction
{
    public function __construct($name)
    {
        parent::__construct(
            $name,
            \Closure::fromCallable($this->compile(...))->bindTo($this),
            \Closure::fromCallable($this->evaluate(...))->bindTo($this)
        );
    }

    private function compile(string $filename, string $length): string
    {
        return <<<PHP
            (
                !\\is_string({$filename}) ?
                null :
                (
                    \\strlen(\$basename = \\pathinfo({$filename}, \\PATHINFO_BASENAME)) > {$length} ?
                        \$basename . '.' . \\pathinfo({$filename}, \\PATHINFO_EXTENSION) :
                        {$filename}
                )
            )
            PHP;
    }

    private function evaluate(array $context, string $filename, int $length)
    {
        return !\is_string($filename) ?
            null :
            (
                \strlen($basename = pathinfo($filename, \PATHINFO_BASENAME)) > $length ?
                    $basename.'.'.pathinfo($filename, \PATHINFO_EXTENSION) :
                    $filename
            );
    }
}
