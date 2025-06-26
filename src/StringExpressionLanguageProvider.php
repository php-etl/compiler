<?php

declare(strict_types=1);

namespace Kiboko\Component\StringExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

class StringExpressionLanguageProvider implements ExpressionFunctionProviderInterface
{
    public function getFunctions(): array
    {
        return [
            ExpressionFunction::fromPhp('sprintf', 'format'),
            ExpressionFunction::fromPhp('trim', 'trim'),
            new Capitalize('capitalize'),
            new ToLowerCase('toLowerCase'),
            ExpressionFunction::fromPhp('mb_substr', 'search'),
            new ToUpperCase('toUpperCase'),
            ExpressionFunction::fromPhp('number_format', 'formatNumber'),
            ExpressionFunction::fromPhp('strpos', 'indexOf'),
            ExpressionFunction::fromPhp('str_replace', 'replace'),
            ExpressionFunction::fromPhp('strip_tags', 'stripHtml'),
            ExpressionFunction::fromPhp('json_decode', 'decode'),
            ExpressionFunction::fromPhp('preg_replace', 'replaceByExpression'),
            new CapitalizeWords('capitalizeWords'),
            ExpressionFunction::fromPhp('rtrim', 'removeWhitespaces'),
            ExpressionFunction::fromPhp('explode', 'splitIntoArray'),
            new Truncate('truncate'),
            new FileName('fileName'),
            new DateTime('dateTime'),
            new FormatDate('formatDate'),
            new TruncateFileName('truncateFileName'),
            new Now('now'),
            new ConvertCharCode('convertCharCode'),
            new AsFloat('asFloat'),
            new AsInteger('asInteger'),
            new AsString('asString'),
        ];
    }
}
