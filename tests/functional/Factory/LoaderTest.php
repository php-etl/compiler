<?php

declare(strict_types=1);

namespace functional\Kiboko\Plugin\Sylius\Factory;

use Kiboko\Contract\Configurator\InvalidConfigurationException;
use Kiboko\Plugin\Sylius\Factory\Loader;
use PHPUnit\Framework\TestCase;

final class LoaderTest extends TestCase
{
    public static function validDataProvider(): \Generator
    {
        yield [
            [
                'type' => 'products',
                'method' => 'create',
            ],
        ];

        yield [
            [
                'type' => 'products',
                'method' => 'upsert',
            ],
        ];
    }

    public static function wrongConfigs(): \Generator
    {
        yield [
            'config' => [
            ],
        ];
        yield [
            'config' => [
                'wrong',
            ],
        ];
        yield [
            'config' => [
                'type' => 'wrong',
                'method' => 'all',
            ],
        ];
        yield [
            'config' => [
                'type' => 'products',
                'method' => 'wrong',
            ],
        ];
        yield [
            'config' => [
                'type' => 'products',
            ],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('validDataProvider')]
    public function testValidateConfiguration(array $config): void
    {
        $client = new Loader();
        $this->assertTrue($client->validate([$config]));
        $client->compile($config);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('wrongConfigs')]
    public function testMissingCapacity(array $config): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('Your Sylius API configuration is using some unsupported capacity, check your "type" and "method" properties to a suitable set.');

        $client = new Loader();
        $this->assertFalse($client->validate($config));
        $client->compile($config);
    }
}
