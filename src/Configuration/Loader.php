<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Prestashop\Configuration;

use Kiboko\Contract\Configurator\PluginConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

final class Loader implements PluginConfigurationInterface
{
    private static array $endpoints = [
        'categories' => [
            'create',
            'update',
            'upsert',
        ],
        'combinations' => [
            'create',
            'update',
            'upsert',
        ],
        'manufacturers' => [
            'create',
            'update',
            'upsert',
        ],
        'product_features' => [
            'create',
            'update',
            'upsert',
        ],
        'product_feature_values' => [
            'create',
            'update',
            'upsert',
        ],
        'product_options' => [
            'create',
            'update',
            'upsert',
        ],
        'product_option_values' => [
            'create',
            'update',
            'upsert',
        ],
        'products' => [
            'create',
            'update',
            'upsert',
        ],
        'shops' => [
            'create',
            'update',
            'upsert',
        ],
        'stock_availables' => [
            'update',
            'upsert',
        ],
        'suppliers' => [
            'create',
            'update',
            'upsert',
        ],
        'tags' => [
            'create',
            'update',
            'upsert',
        ],
        'tax_rule_groups' => [
            'create',
            'update',
            'upsert',
        ],
        'tax_rules' => [
            'create',
            'update',
            'upsert',
        ],
        'product_images' => [
            'upload',
        ],
    ];

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder('loader');

        /* @phpstan-ignore-next-line */
        $builder->getRootNode()
            ->validate()
            ->ifArray()
                ->then(function (array $item) {
                    if (!\in_array($item['method'], self::$endpoints[$item['type']])) {
                        throw new \InvalidArgumentException(\sprintf('the value should be one of [%s], got %s', implode(', ', self::$endpoints[$item['type']]), json_encode($item['method'], \JSON_THROW_ON_ERROR)));
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
                            \sprintf('the value should be one of [%s]', implode(', ', array_keys(self::$endpoints)))
                        )
                    ->end()
                ->end()
                ->scalarNode('method')->isRequired()->end()
                ->scalarNode('id')->end()
                ->arrayNode('options')
                    ->children()
                        ->scalarNode('id_shop')->end()
                        ->scalarNode('id_group_shop')->end()
                ->end()
            ->end()
        ;

        return $builder;
    }
}
