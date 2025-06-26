<?php declare(strict_types=1);


namespace functional\Kiboko\Plugin\JSON\Factory;

use Kiboko\Contract\Configurator\InvalidConfigurationException;
use Kiboko\Plugin\JSON;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class FactoryExtractorTest extends TestCase
{
    public static function configProvider()
    {
        yield [
            'expected' => [
                'file_path' => 'input.jsonld'
            ],
            'actual' => [
                'extractor' => [
                    'file_path' => 'input.jsonld'
                ]
            ]
        ];
    }

    #[DataProvider('configProvider')]
    public function testWithConfiguration(array $expected, array $actual): void
    {
        $factory = new JSON\Factory\Extractor();
        $normalizedConfig = $factory->normalize($actual);

        $this->assertEquals(
            new JSON\Configuration\Extractor(),
            $factory->configuration()
        );

        $this->assertEquals(
            $expected,
            $normalizedConfig
        );

        $this->assertTrue($factory->validate($actual));
    }

    public function testFailToNormalize(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('The child config "file_path" under "extractor" must be configured.');

        $wrongConfig = [
            'extractor' => []
        ];

        $factory = new JSON\Factory\Extractor();
        $factory->normalize($wrongConfig);
    }
}
