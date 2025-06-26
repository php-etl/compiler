<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Prestashop\Capacity;

use Kiboko\Plugin\Prestashop;
use PhpParser\Builder;
use PhpParser\Node;

final class Upsert implements CapacityInterface
{
    private static array $endpoints = [
        'categories',
        'combinations',
        'manufacturers',
        'product_features',
        'product_feature_values',
        'product_options',
        'product_option_values',
        'products',
        'shops',
        'stock_availables',
        'suppliers',
        'tags',
        'tax_rule_groups',
        'tax_rules',
    ];

    public function applies(array $config): bool
    {
        return isset($config['type'])
            && \in_array($config['type'], self::$endpoints)
            && isset($config['method'])
            && 'upsert' === $config['method'];
    }

    public function getBuilder(array $config): Builder
    {
        return (new Prestashop\Builder\Capacity\Upsert($config['options'] ?? []))
            ->withEndpoint(new Node\Identifier(\sprintf('get%sApi', ucfirst((string) $config['type']))))
            ->withData(line: new Node\Expr\Variable('line'))
        ;
    }
}
