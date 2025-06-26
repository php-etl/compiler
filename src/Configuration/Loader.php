<?php

declare(strict_types=1);

namespace Kiboko\Plugin\JSON\Configuration;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Loader implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder('loader');

        $builder->getRootNode()
            ->children()
                ->scalarNode('file_path')->isRequired()->end()
            ->end()
        ;

        return $builder;
    }
}
