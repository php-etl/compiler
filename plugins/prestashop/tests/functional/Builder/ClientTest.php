<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\Prestashop\Builder;

use Kiboko\Component\Prestashop\ApiClient\PrestashopClient;
use Kiboko\Plugin\Prestashop\Builder\Client;
use PhpParser\Node;

final class ClientTest extends BuilderTestCase
{
    public function testExpectingTokenOrPassword(): void
    {
        $client = new Client(
            new Node\Scalar\String_('http://localhost:8001'),
            new Node\Scalar\String_('1VN86447KTXYCA51DGKYKJTGHDBE8T5Z')
        );

        $this->assertNodeIsInstanceOf(PrestashopClient::class, $client);

        $client->getNode();
    }
}
