<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Prestashop\Capacity;

use Kiboko\Plugin\Prestashop;
use PhpParser\Builder;
use PhpParser\Node;

final class Upload implements CapacityInterface
{
    private static array $endpoints = [
        'product_images',
    ];

    public function applies(array $config): bool
    {
        return isset($config['type'])
            && \in_array($config['type'], self::$endpoints)
            && isset($config['method'])
            && 'upload' === $config['method'];
    }

    public function getBuilder(array $config): Builder
    {
        return (new Prestashop\Builder\Capacity\Upload($config['options'] ?? []))
            ->withEndpoint(new Node\Identifier(\sprintf('get%sApi', ucfirst((string) $config['type']))))
            ->withData(line: new Node\Expr\Variable('line'))
        ;
    }
}
