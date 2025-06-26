<?php

declare(strict_types=1);

namespace Kiboko\Plugin\CSV\Configuration;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

use function Kiboko\Component\SatelliteToolbox\Configuration\asExpression;
use function Kiboko\Component\SatelliteToolbox\Configuration\isExpression;
use function Kiboko\Component\SatelliteToolbox\Configuration\mutuallyExclusiveFields;

final class Loader implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder('loader');

        /* @phpstan-ignore-next-line */
        $builder->getRootNode()
            ->beforeNormalization()
            ->always(mutuallyExclusiveFields('nonstandard', 'enclosure'))
            ->always(mutuallyExclusiveFields('nonstandard', 'escape'))
            ->end()
            ->children()
                ->scalarNode('file_path')
                    ->isRequired()
                    ->validate()
                        ->ifTrue(isExpression())
                        ->then(asExpression())
                    ->end()
                ->end()
                ->integerNode('max_lines')
                    ->min(1)
                    ->validate()
                        ->ifTrue(isExpression())
                        ->then(asExpression())
                    ->end()
                ->end()
                ->scalarNode('delimiter')
                    ->validate()
                        ->ifTrue(isExpression())
                        ->then(asExpression())
                    ->end()
                ->end()
                ->scalarNode('enclosure')
                    ->validate()
                        ->ifTrue(isExpression())
                        ->then(asExpression())
                    ->end()
                ->end()
                ->scalarNode('escape')
                    ->validate()
                        ->ifTrue(isExpression())
                        ->then(asExpression())
                    ->end()
                ->end()
                ->booleanNode('safe_mode')->end()
                ->variableNode('columns')
                    ->validate()
                        ->ifTrue(fn ($value) => null !== $value && !\is_array($value))
                        ->thenInvalid('Value should be an array')
                    ->end()
                    ->validate()
                        ->ifTrue(fn ($value) => null === $value)
                        ->thenInvalid('Value cannot be null')
                    ->end()
                ->end()
                ->booleanNode('nonstandard')->end()
            ->end()
        ;

        return $builder;
    }
}
