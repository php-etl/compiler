<?php declare(strict_types=1);

namespace functional\Factory;

use Kiboko\Contract\Configurator\InvalidConfigurationException;
use Kiboko\Plugin\CSV;
use PHPUnit\Framework\TestCase;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

final class LoaderTest extends TestCase
{
    public static function configProvider()
    {
        yield [
            'expected' => [
                'file_path' => 'output.csv',
            ],
            'actual' => [
                'loader' => [
                    'file_path' => 'output.csv'
                ]
            ]
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('configProvider')]
    public function testWithConfiguration(array $expected, array $actual): void
    {
        $factory = new CSV\Factory\Loader(new ExpressionLanguage());
        $normalizedConfig = $factory->normalize($actual);

        $this->assertEquals(
            new CSV\Configuration\Loader(),
            $factory->configuration()
        );

        $this->assertEquals(
            $expected,
            $normalizedConfig
        );

        $this->assertTrue(
            $factory->validate($actual)
        );
    }

    public function testFailToNormalize(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('The child config "file_path" under "loader" must be configured.');

        $wrongConfig = [
            'loader' => []
        ];

        $factory = new CSV\Factory\Loader(new ExpressionLanguage());
        $factory->normalize($wrongConfig);
    }


    public function testFailToValidate(): void
    {
        $factory = new CSV\Factory\Loader(new ExpressionLanguage());
        $this->assertFalse($factory->validate([]));
    }
}
