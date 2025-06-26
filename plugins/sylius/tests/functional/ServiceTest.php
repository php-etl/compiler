<?php

declare(strict_types=1);

namespace functional\Kiboko\Plugin\Sylius;

use Kiboko\Contract\Configurator\InvalidConfigurationException;
use Kiboko\Plugin\Sylius;
use PHPUnit\Framework\TestCase;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

final class ServiceTest extends TestCase
{
    public static function validDataProvider(): \Generator
    {
        /* Get */
        yield [
            'expected' => [
                'expression_language' => [],
                'extractor' => [
                    'type' => 'products',
                    'method' => 'all',
                    'search' => [],
                ],
                'client' => [
                    'api_url' => '1234',
                    'client_id' => '1234',
                    'secret' => '1234',
                    'username' => '1234',
                    'password' => '1234',
                ],
            ],
            'actual' => [
                'extractor' => [
                    'type' => 'products',
                    'method' => 'all',
                ],
                'client' => [
                    'api_url' => '1234',
                    'client_id' => '1234',
                    'secret' => '1234',
                    'username' => '1234',
                    'password' => '1234',
                ],
            ],
        ];

        /* Upsert */
        yield [
            'expected' => [
                'expression_language' => [],
                'loader' => [
                    'type' => 'products',
                    'method' => 'upsert',
                ],
                'client' => [
                    'api_url' => '1234',
                    'client_id' => '1234',
                    'secret' => '1234',
                    'username' => '1234',
                    'password' => '1234',
                ],
            ],
            'actual' => [
                'expression_language' => [],
                'loader' => [
                    'type' => 'products',
                    'method' => 'upsert',
                ],
                'client' => [
                    'api_url' => '1234',
                    'client_id' => '1234',
                    'secret' => '1234',
                    'username' => '1234',
                    'password' => '1234',
                ],
            ],
        ];
    }

    public function testEmptyConfiguration(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('Could not determine if the factory should build an extractor or a loader.');

        $service = new Sylius\Service();
        $this->assertTrue($service->validate(['sylius' => []]));
        $service->compile([
            'sylius' => [],
        ]);
    }

    public function testWrongConfiguration(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('Invalid type for path "sylius". Expected "array", but got "string"');

        $service = new Sylius\Service();
        $this->assertFalse($service->validate(['sylius' => 'wrong']));
        $service->normalize(['sylius' => 'wrong']);
    }

    public function testMissingAuthentication(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('Your Sylius API configuration is missing an authentication method, you should either define "username" or "token" options.');

        $service = new Sylius\Service();
        $service->compile([
            'loader' => [
                'type' => 'products',
                'method' => 'upsert',
                'code' => 'azerty123',
            ],
            'client' => [
                'api_url' => '1234',
                'client_id' => '1234',
                'secret' => '1234',
                'username' => '1234',
            ],
        ]);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('validDataProvider')]
    public function testWithConfigurationAndProcessor(array $expected, array $actual): void
    {
        $service = new Sylius\Service(new ExpressionLanguage());

        $this->assertEquals(
            $expected,
            $service->normalize([$actual])
        );

        $this->assertTrue($service->validate([$actual]));
        $service->compile($actual);
    }
}
