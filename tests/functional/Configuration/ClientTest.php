<?php

declare(strict_types=1);

namespace functional\Kiboko\Plugin\Sylius\Configuration;

use Kiboko\Plugin\Sylius\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config;

final class ClientTest extends TestCase
{
    private ?Config\Definition\Processor $processor = null;

    protected function setUp(): void
    {
        $this->processor = new Config\Definition\Processor();
    }

    public function testValidConfigWithPasswordAuthentication(): void
    {
        $client = new Configuration\Client();

        $this->assertSame(
            [
                'context' => [],
                'api_url' => 'http://api.example.com',
                'client_id' => 'LOREMIPSUM',
                'secret' => 'SECRET',
                'username' => 'JOHNDOE',
                'password' => 'PASSWORD',
            ],
            $this->processor->processConfiguration(
                $client,
                [
                    [
                        'context' => [],
                        'api_url' => 'http://api.example.com',
                        'client_id' => 'LOREMIPSUM',
                        'secret' => 'SECRET',
                        'username' => 'JOHNDOE',
                        'password' => 'PASSWORD',
                    ],
                ]
            )
        );
    }

    public function testValidConfigWithTokenAuthentication(): void
    {
        $client = new Configuration\Client();

        $this->assertSame(
            [
                'context' => [],
                'api_url' => 'http://api.example.com',
                'client_id' => 'LOREMIPSUM',
                'secret' => 'SECRET',
                'token' => 'TOKEN',
                'refresh_token' => 'REFRESH',
            ],
            $this->processor->processConfiguration(
                $client,
                [
                    [
                        'context' => [],
                        'api_url' => 'http://api.example.com',
                        'client_id' => 'LOREMIPSUM',
                        'secret' => 'SECRET',
                        'token' => 'TOKEN',
                        'refresh_token' => 'REFRESH',
                    ],
                ]
            )
        );
    }

    public function testMissingAuthenticationMethod(): void
    {
        $client = new Configuration\Client();

        $this->expectException(
            Config\Definition\Exception\InvalidConfigurationException::class,
        );
        $this->expectExceptionMessage(
            'You must choose between "username" and "token" as authentication method for Sylius API, both are mutually exclusive.',
        );

        $this->processor->processConfiguration(
            $client,
            [
                [
                    'context' => [],
                    'api_url' => 'http://api.example.com',
                    'client_id' => 'LOREMIPSUM',
                    'secret' => 'SECRET',
                ],
            ]
        );
    }

    public function testBothAuthenticationMethod(): void
    {
        $client = new Configuration\Client();

        $this->expectException(
            Config\Definition\Exception\InvalidConfigurationException::class,
        );
        $this->expectExceptionMessage(
            'You must choose between "username" and "token" as authentication method for Sylius API, both are mutually exclusive.',
        );

        $this->processor->processConfiguration(
            $client,
            [
                [
                    'context' => [],
                    'api_url' => 'http://api.example.com',
                    'client_id' => 'LOREMIPSUM',
                    'secret' => 'SECRET',
                    'username' => 'JOHNDOE',
                    'password' => 'PASSWORD',
                    'token' => 'TOKEN',
                    'refresh_token' => 'REFRESH',
                ],
            ]
        );
    }

    public function testMissingPasswordInAuthenticationMethod(): void
    {
        $client = new Configuration\Client();

        $this->expectException(
            Config\Definition\Exception\InvalidConfigurationException::class,
        );
        $this->expectExceptionMessage(
            'The configuration option "password" should be defined if you use the username authentication method for Sylius API.',
        );

        $this->processor->processConfiguration(
            $client,
            [
                [
                    'context' => [],
                    'api_url' => 'http://api.example.com',
                    'client_id' => 'LOREMIPSUM',
                    'secret' => 'SECRET',
                    'username' => 'JOHNDOE',
                ],
            ]
        );
    }

    public function testMissingRefreshTokenInAuthenticationMethod(): void
    {
        $client = new Configuration\Client();

        $this->expectException(
            Config\Definition\Exception\InvalidConfigurationException::class,
        );
        $this->expectExceptionMessage(
            'The configuration option "refreshToken" should be defined if you use the token authentication method for Sylius API.',
        );

        $this->processor->processConfiguration(
            $client,
            [
                [
                    'context' => [],
                    'api_url' => 'http://api.example.com',
                    'client_id' => 'LOREMIPSUM',
                    'secret' => 'SECRET',
                    'token' => 'TOKEN',
                ],
            ]
        );
    }
}
