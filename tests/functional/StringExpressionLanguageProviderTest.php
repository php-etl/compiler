<?php

namespace functional\Kiboko\Component\StringExpressionLanguage;

use Kiboko\Component\StringExpressionLanguage\StringExpressionLanguageProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class StringExpressionLanguageProviderTest extends TestCase
{
    public function testFormatExpression(): void
    {
        $interpreter = new ExpressionLanguage(null, [new StringExpressionLanguageProvider()]);

        $this->assertEquals('SKU_000001', $interpreter->evaluate('format("SKU_%06d", 1)'));
    }

    public function testExpression(): void
    {
        $interpreter = new ExpressionLanguage(null, [new StringExpressionLanguageProvider()]);

        $this->assertEquals('SKU_000001', $interpreter->evaluate('fileName("SKU_000001")'));
    }
}
