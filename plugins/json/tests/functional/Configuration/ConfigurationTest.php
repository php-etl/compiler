<?php declare(strict_types=1);


namespace functional\Kiboko\Plugin\JSON\Configuration;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Kiboko\Plugin\JSON\Configuration;
use Symfony\Component\Config;

final class ConfigurationTest extends TestCase
{
    private ?Config\Definition\Processor $processor = null;

    protected function setUp(): void
    {
        $this->processor = new Config\Definition\Processor();
    }

    static public function validConfigProvider(): \Generator
    {
        /* Minimal config */
        yield [
            'expected' => [
                'extractor' => [
                    'file_path' => 'path/to/file'
                ]
            ],
            'actual' => [
                'extractor' => [
                    'file_path' => 'path/to/file'
                ]
            ]
        ];
    }

    #[DataProvider('validConfigProvider')]
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
        $this->expectExceptionMessage('The child config "file_path" under "json.extractor" must be configured.');

        $config = new Configuration();
        $this->processor->processConfiguration(
            $config,
            [
                [
                    'extractor' => []
                ]
            ]
        );
    }
}
