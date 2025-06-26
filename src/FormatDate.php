<?php

declare(strict_types=1);

namespace Kiboko\Component\StringExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;

class FormatDate extends ExpressionFunction
{
    public function __construct($name)
    {
        parent::__construct(
            $name,
            $this->compile(...)->bindTo($this),
            $this->evaluate(...)->bindTo($this)
        );
    }

    private function compile(string $dateTime, string $format): string
    {
        return <<<"PHP"
                {$dateTime}->format({$format})
            PHP;
    }

    private function evaluate(array $context, \DateTimeInterface $dateTime, string $format)
    {
        return $dateTime->format($format);
    }
}
