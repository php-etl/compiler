<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\Prestashop\Configuration;

use Kiboko\Plugin\Prestashop\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config;

final class ClientTest extends TestCase
{
    private ?Config\Definition\Processor $processor = null;

    protected function setUp(): void
    {
        $this->processor = new Config\Definition\Processor();
    }

    public function testValidConfigWithPasswordAuthentication()
    {
        $client = new Configuration\Client();

        $this->assertSame(
            [
                'url' => 'http://api.example.com',
                'api_key' => '12345',
            ],
            $this->processor->processConfiguration(
                $client,
                [
                    [
                        'url' => 'http://api.example.com',
                        'api_key' => '12345',
                    ]
                ]
            )
        );
    }
}
