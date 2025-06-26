<?php

declare(strict_types=1);

namespace functional\Kiboko\Plugin\Prestashop\Configuration;

use Kiboko\Plugin\Prestashop\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config;

final class ExtractorTest extends TestCase
{
    private ?Config\Definition\Processor $processor = null;

    protected function setUp(): void
    {
        $this->processor = new Config\Definition\Processor();
    }

    public static function validDataProvider(): iterable
    {
        yield [
            'config' => [
                'type' => 'products',
                'method' => 'all',
            ],
            'expected' => [
                'type' => 'products',
                'method' => 'all',
            ],
        ];
        yield [
            'config' => [
                'type' => 'suppliers',
                'method' => 'all',
                'options' => [
                    'columns' => [
                        'id'
                    ],
                ],
            ],
            'expected' => [
                'type' => 'suppliers',
                'method' => 'all',
                'options' => [
                    'columns' => [
                        'id'
                    ],
                    'filter' => [],
                    'sorters' => [],
                    'languages' => [],
                    'price' => [],
                ],
            ],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('validDataProvider')]
    public function testValidConfig(array $config, array $expected)
    {
        $client = new Configuration\Extractor();

        $this->assertEquals($expected, $this->processor->processConfiguration($client, [$config]));
    }

    public function testInvalidMethodTypeConfig()
    {
        $client = new Configuration\Extractor();

        $this->expectException(
            Config\Definition\Exception\InvalidConfigurationException::class,
        );
        $this->expectExceptionMessage(
            'Unrecognized option "foo" under "extractor.options". Available options are "columns", "filter", "id_group_shop", "id_shop", "languages", "price", "sorters".',
        );

        $this->processor->processConfiguration($client, [
            [
                'options' => ['foo' => 'bar'],
            ]
        ]);
    }
}
