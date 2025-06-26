<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Sylius\Configuration;

use Symfony\Component\Config;

use function Kiboko\Component\SatelliteToolbox\Configuration\asExpression;
use function Kiboko\Component\SatelliteToolbox\Configuration\isExpression;

final class Extractor implements Config\Definition\ConfigurationInterface
{
    private static array $endpoints = [
        // Core Endpoints
        'channels' => [
            'listPerPage',
            'all',
            'get',
        ],
        'countries' => [
            'listPerPage',
            'all',
            'get',
        ],
        'carts' => [
            'listPerPage',
            'all',
            'get',
        ],
        'currencies' => [
            'listPerPage',
            'all',
            'get',
        ],
        'customers' => [
            'listPerPage',
            'all',
            'get',
        ],
        'exchangeRates' => [
            'listPerPage',
            'all',
            'get',
        ],
        'locales' => [
            'listPerPage',
            'all',
            'get',
        ],
        'orders' => [
            'listPerPage',
            'all',
            'get',
        ],
        'payments' => [
            'listPerPage',
            'all',
            'get',
        ],
        'paymentMethods' => [
            'listPerPage',
            'all',
            'get',
        ],
        'products' => [
            'listPerPage',
            'all',
            'get',
        ],
        'productAttributes' => [
            'listPerPage',
            'all',
            'get',
        ],
        'productAssociationTypes' => [
            'listPerPage',
            'all',
            'get',
        ],
        'productOptions' => [
            'listPerPage',
            'all',
            'get',
        ],
        'promotions' => [
            'listPerPage',
            'all',
            'get',
        ],
        'shipments' => [
            'listPerPage',
            'all',
            'get',
        ],
        'shippingCategories' => [
            'listPerPage',
            'all',
            'get',
        ],
        'taxCategories' => [
            'listPerPage',
            'all',
            'get',
        ],
        'taxRates' => [
            'listPerPage',
            'all',
            'get',
        ],
        'taxons' => [
            'listPerPage',
            'all',
            'get',
        ],
        'users' => [
            'listPerPage',
            'all',
            'get',
        ],
        'zones' => [
            'listPerPage',
            'all',
            'get',
        ],
    ];

    private static array $doubleEndpoints = [
        // Double resources Endpoints
        'productReviews',
        'productVariants',
        'promotionCoupons',
    ];

    public function getConfigTreeBuilder(): \Symfony\Component\Config\Definition\Builder\TreeBuilder
    {
        $filters = new Search();

        $builder = new Config\Definition\Builder\TreeBuilder('extractor');

        /* @phpstan-ignore-next-line */
        $builder->getRootNode()
            ->validate()
            ->ifArray()
            ->then(function (array $item) {
                if (
                    \array_key_exists($item['type'], self::$endpoints)
                    && !\in_array($item['method'], self::$endpoints[$item['type']])
                    && !\in_array($item['type'], self::$doubleEndpoints)
                ) {
                    throw new \InvalidArgumentException(sprintf('The value should be one of [%s], got %s.', implode(', ', self::$endpoints[$item['type']]), json_encode($item['method'], \JSON_THROW_ON_ERROR)));
                }

                return $item;
            })
            ->end()
            ->validate()
                ->ifArray()
                ->then(function (array $item) {
                    if (\in_array($item['type'], self::$doubleEndpoints) && !\array_key_exists('code', $item)) {
                        throw new \InvalidArgumentException(sprintf('The %s type should have a "code" field set.', $item['type']));
                    }

                    return $item;
                })
            ->end()
            ->children()
                ->scalarNode('type')
                    ->isRequired()
                    ->validate()
                        ->ifNotInArray(array_merge(array_keys(self::$endpoints), self::$doubleEndpoints))
                        ->thenInvalid(
                            sprintf(
                                'the value should be one of [%s], got %%s',
                                implode(', ', array_merge(array_keys(self::$endpoints), self::$doubleEndpoints))
                            )
                        )
                    ->end()
                ->end()
                ->scalarNode('method')->end()
                ->scalarNode('code')
                    ->validate()
                        ->ifTrue(isExpression())
                        ->then(asExpression())
                    ->end()
                ->end()
                ->append(node: $filters->getConfigTreeBuilder()->getRootNode())
            ->end()
        ;

        return $builder;
    }
}
