<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\Prestashop\Builder\Capacity\Loader;

use functional\Kiboko\Plugin\Prestashop\Builder\BuilderTestCase;
use Kiboko\Plugin\Prestashop\Builder\Client;
use Kiboko\Plugin\Prestashop\Builder\Loader;
use PhpParser\Node;
use Kiboko\Plugin\Prestashop\Capacity;

final class LoaderTest extends BuilderTestCase
{
    public function testUpdate()
    {
        $client = new Client(
            new Node\Scalar\String_('http://localhost:8001'),
            new Node\Scalar\String_('1VN86447KTXYCA51DGKYKJTGHDBE8T5Z')
        );

        $capacity = (new Capacity\Update())->getBuilder(['type' => 'products']);

        $builder = new Loader($capacity);

        $builder->withClient($client->getNode());

        $this->assertInstanceOf(Node\Expr\New_::class, $builder->getNode());
    }

    public function testCreate()
    {
        $client = new Client(
            new Node\Scalar\String_('http://localhost:8001'),
            new Node\Scalar\String_('1VN86447KTXYCA51DGKYKJTGHDBE8T5Z')
        );

        $capacity = (new Capacity\Create())->getBuilder(['type' => 'products']);

        $builder = new Loader($capacity);

        $builder->withClient($client->getNode());

        $this->assertInstanceOf(Node\Expr\New_::class, $builder->getNode());
    }
}
