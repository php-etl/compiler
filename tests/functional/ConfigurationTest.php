<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\CSV;

use Kiboko\Plugin\CSV\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config;

class ConfigurationTest extends TestCase
{
    private ?Config\Definition\Processor $processor = null;

    protected function setUp(): void
    {
        $this->processor = new Config\Definition\Processor();
    }

    public static function validConfigProvider()
    {
        yield [
            'expected' => [
                'expression_language' => [],
                'extractor' => [
                    'file_path' => 'path/to/file',
                    'delimiter' => ';',
                    'enclosure' => '"',
                    'escape' => '\\',
                ]
            ],
            'actual' => [
                'expression_language' => [],
                'extractor' => [
                    'file_path' => 'path/to/file',
                    'delimiter' => ';',
                    'enclosure' => '"',
                    'escape' => '\\',
                ]
            ]
        ];

        yield [
            'expected' => [
                'expression_language' => [],
                'extractor' => [
                    'file_path' => 'path/to/file',
                    'delimiter' => ',',
                    'enclosure' => '"',
                ]
            ],
            'actual' => [
                'expression_language' => [],
                'extractor' => [
                    'file_path' => 'path/to/file',
                    'delimiter' => ',',
                    'enclosure' => '"',
                ]
            ]
        ];

        yield [
            'expected' => [
                'expression_language' => [],
                'extractor' => [
                    'file_path' => 'path/to/file',
                    'delimiter' => ',',
                    'escape' => '\\'
                ]
            ],
            'actual' => [
                'expression_language' => [],
                'extractor' => [
                    'file_path' => 'path/to/file',
                    'delimiter' => ',',
                    'escape' => '\\',
                ]
            ]
        ];

        yield [
            'expected' => [
                'expression_language' => [],
                'extractor' => [
                    'file_path' => 'path/to/file',
                    'enclosure' => '"',
                    'escape' => '\\'
                ]
            ],
            'actual' => [
                'expression_language' => [],
                'extractor' => [
                    'file_path' => 'path/to/file',
                    'enclosure' => '"',
                    'escape' => '\\',
                ]
            ]
        ];

        yield [
            'expected' => [
                'expression_language' => [],
                'extractor' => [
                    'file_path' => 'path/to/file',
                    'delimiter' => ',',
                    'enclosure' => '"',
                    'escape' => '\\'
                ]
            ],
            'actual' => [
                'expression_language' => [],
                'extractor' => [
                    'file_path' => 'path/to/file',
                    'delimiter' => ',',
                    'enclosure' => '"',
                    'escape' => '\\'
                ]
            ]
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('validConfigProvider')]
    public function testValidConfig($expected, $actual)
    {
        $config = new Configuration();

        $this->assertEquals(
            $expected,
            $this->processor->processConfiguration(
                $config,
                [
                    $actual
                ]
            )
        );
    }

    public function testMissingFilePath()
    {
        $this->expectException(Config\Definition\Exception\InvalidConfigurationException::class);
        $this->expectExceptionMessage('The child config "file_path" under "csv.extractor" must be configured.');

        $config = new Configuration();
        $this->processor->processConfiguration(
            $config,
            [
                [
                    'extractor' => [
                        'enclosure' => '"',
                    ]
                ]
            ]
        );
    }

    public function testMissingOptionsInLoader()
    {
        $this->expectException(Config\Definition\Exception\InvalidConfigurationException::class);
        $this->expectExceptionMessage('The child config "file_path" under "csv.loader" must be configured.');

        $config = new Configuration();
        $this->processor->processConfiguration(
            $config,
            [
                [
                    'loader' => [
                    ]
                ]
            ]
        );
    }
}
