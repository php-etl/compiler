<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\JSON\Service;

use Kiboko\Contract\Configurator\InvalidConfigurationException;
use Kiboko\Plugin\JSON;
use Kiboko\Plugin\JSON\Factory\Repository\Extractor;
use Kiboko\Plugin\JSON\Factory\Repository\Loader;
use PHPUnit\Framework\TestCase;

final class ServiceTest extends TestCase
{
    public static function configProvider()
    {
        yield [
            'expected' => [
                'extractor' => [
                    'file_path' => 'path/to/file'
                ]
            ],
            'expected_class' => Extractor::class,
            'actual' => [
                'json' => [
                    'extractor' => [
                        'file_path' => 'path/to/file'
                    ]
                ]
            ]
        ];

        yield [
            'expected' => [
                'loader' => [
                    'file_path' => 'output.jsonld'
                ]
            ],
            'expected_class' => Loader::class,
            'actual' => [
                'json' => [
                    'loader' => [
                        'file_path' => 'output.jsonld'
                    ]
                ]
            ]
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('configProvider')]
    public function testWithConfigurationAndProcessor(array $expected, string $expectedClass, array $actual): void
    {
        $service = new JSON\Service();
        $normalizedConfig = $service->normalize($actual);

        $this->assertEquals(
            new JSON\Configuration(),
            $service->configuration()
        );

        $this->assertEquals(
            $expected,
            $normalizedConfig
        );

        $this->assertTrue($service->validate($actual));

        $this->assertInstanceOf(
            $expectedClass,
            $service->compile($normalizedConfig)
        );
    }

    public function testWithBothExtractAndLoad(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('Your configuration should either contain the "extractor" or the "loader" key, not both.');

        $wrongConfig = [
            'spreadsheet' => [
                'extractor' => [
                    'file_path' => 'input.jsonld'
                ],
                'loader' => [
                    'file_path' => 'output.jsonld'
                ]
            ]
        ];

        $service = new JSON\Service();
        $service->normalize($wrongConfig);
    }

    public function testWrongConfiguration(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('Could not determine if the factory should build an extractor or a loader.');

        $service = new JSON\Service();
        $service->compile([
            'spreadsheet' => []
        ]);
    }
}
