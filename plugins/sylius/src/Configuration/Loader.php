<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Sylius\Configuration;

use Symfony\Component\Config;

final class Loader implements Config\Definition\ConfigurationInterface
{
    private static array $endpoints = [
        // Core Endpoints
        'channels' => [
            'create',
            'delete',
        ],
        'countries' => [
            'create',
            'delete',
        ],
        'carts' => [
            'create',
            'delete',
        ],
        'currencies' => [
            'create',
            'delete',
        ],
        'customers' => [
            'create',
            'delete',
        ],
        'exchangeRates' => [
            'create',
            'delete',
        ],
        'locales' => [
            'create',
            'delete',
        ],
        'orders' => [
            'create',
            'delete',
        ],
        'payments' => [
            'create',
            'delete',
        ],
        'paymentMethods' => [
            'create',
            'delete',
        ],
        'products' => [
            'create',
            'upsert',
            'delete',
        ],
        'productAttributes' => [
            'create',
            'delete',
        ],
        'productAssociationTypes' => [
            'create',
            'delete',
        ],
        'productOptions' => [
            'create',
            'delete',
        ],
        'promotions' => [
            'create',
            'delete',
        ],
        'shipments' => [
            'create',
            'delete',
        ],
        'shippingCategories' => [
            'create',
            'delete',
        ],
        'taxCategories' => [
            'create',
            'delete',
        ],
        'taxRates' => [
            'create',
            'delete',
        ],
        'taxons' => [
            'create',
            'delete',
        ],
        'users' => [
            'create',
            'delete',
        ],
        'zones' => [
            'create',
            'delete',
        ],
    ];

    public function getConfigTreeBuilder(): \Symfony\Component\Config\Definition\Builder\TreeBuilder
    {
        $builder = new Config\Definition\Builder\TreeBuilder('loader');

        /* @phpstan-ignore-next-line */
        $builder->getRootNode()
            ->validate()
            ->ifArray()
                ->then(function (array $item) {
                    if (!\in_array($item['method'], self::$endpoints[$item['type']])) {
                        throw new \InvalidArgumentException(sprintf('the value should be one of [%s], got %s', implode(', ', self::$endpoints[$item['type']]), json_encode($item['method'], \JSON_THROW_ON_ERROR)));
                    }

                    return $item;
                })
            ->end()
            ->children()
                ->scalarNode('type')
                    ->isRequired()
                    ->validate()
                        ->ifNotInArray(array_keys(self::$endpoints))
                        ->thenInvalid(
                            sprintf('the value should be one of [%s]', implode(', ', array_keys(self::$endpoints)))
                        )
                    ->end()
                ->end()
                ->scalarNode('method')->end()
            ->end()
        ;

        return $builder;
    }
}
