<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Prestashop\Configuration;

use Kiboko\Contract\Configurator\PluginConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

use function Kiboko\Component\SatelliteToolbox\Configuration\asExpression;
use function Kiboko\Component\SatelliteToolbox\Configuration\isExpression;

final class Client implements PluginConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder('client');

        /* @phpstan-ignore-next-line */
        $builder->getRootNode()
            ->children()
                ->scalarNode('url')->isRequired()->cannotBeEmpty()
                    ->validate()
                        ->ifTrue(isExpression())
                        ->then(asExpression())
                    ->end()
                ->end()
                ->scalarNode('api_key')->isRequired()->cannotBeEmpty()
                    ->validate()
                        ->ifTrue(isExpression())
                        ->then(asExpression())
                    ->end()
                ->end()
            ->end()
        ;

        return $builder;
    }
}
