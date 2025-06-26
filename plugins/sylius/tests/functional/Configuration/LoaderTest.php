<?php

declare(strict_types=1);

namespace functional\Configuration;

use Kiboko\Plugin\Sylius\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config;

final class LoaderTest extends TestCase
{
    private ?Config\Definition\Processor $processor = null;

    protected function setUp(): void
    {
        $this->processor = new Config\Definition\Processor();
    }

    public function testWrongMethod(): void
    {
        $client = new Configuration\Loader();

        $this->expectException(
            Config\Definition\Exception\InvalidConfigurationException::class,
        );
        $this->expectExceptionMessage(
            'Invalid configuration for path "loader": the value should be one of [create, upsert, delete], got "invalidValue"',
        );

        $this->processor->processConfiguration($client, [
            [
                'type' => 'products',
                'method' => 'invalidValue',
            ],
        ]);
    }
}
