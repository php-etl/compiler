<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\Prestashop\Factory;

use Kiboko\Contract\Configurator\InvalidConfigurationException;
use Kiboko\Plugin\Prestashop\Factory\Loader;
use PHPUnit\Framework\TestCase;

final class LoaderTest extends TestCase
{
    public function testNormalizeEmptyConfiguration()
    {
        $this->expectException(
            InvalidConfigurationException::class,
        );

        $client = new Loader();
        $client->normalize([]);
    }

    public function testValidateEmptyConfiguration()
    {
        $client = new Loader();
        $this->assertFalse($client->validate([]));
    }
}
