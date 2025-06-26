<?php

declare(strict_types=1);

namespace functional\Kiboko\Plugin\Sylius\Builder\Capacity;

use Kiboko\Plugin\Sylius\Builder\Capacity\ListPerPage;
use Kiboko\Plugin\Sylius\MissingEndpointException;
use PhpParser\Node;
use PHPUnit\Framework\TestCase;

final class ListPerPageTest extends TestCase
{
    public function testWithoutEndpoint(): void
    {
        $capacity = new ListPerPage();

        $this->expectException(MissingEndpointException::class);
        $this->expectExceptionMessage('Please check your capacity builder, you should have selected an endpoint.');

        $capacity->getNode();
    }

    public function testWithEndpoint(): void
    {
        $capacity = new ListPerPage();

        $capacity->withEndpoint(new Node\Identifier('foo'));

        $this->assertInstanceOf(Node\Stmt\Expression::class, $capacity->getNode());
    }
}
