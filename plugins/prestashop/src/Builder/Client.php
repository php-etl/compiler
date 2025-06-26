<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Prestashop\Builder;

use PhpParser\Builder;
use PhpParser\Node;

final readonly class Client implements Builder
{
    public function __construct(private Node\Expr $url, private Node\Expr $apiKey)
    {
    }

    public function getNode(): Node
    {
        return new Node\Expr\MethodCall(
            var: new Node\Expr\New_(
                class: new Node\Name\FullyQualified(\Kiboko\Component\Prestashop\ApiClient\ClientFactory::class),
            ),
            name: new Node\Identifier('buildClient'),
            args: [
                new Node\Arg($this->url),
                new Node\Arg($this->apiKey),
            ]
        );
    }
}
