<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Prestashop\Capacity;

use Kiboko\Plugin\Prestashop;
use PhpParser\Builder;
use PhpParser\Node;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

final class Get implements CapacityInterface
{
    private static array $endpoints = [
        'categories',
        'combinations',
        'manufacturers',
        'product_features',
        'product_feature_values',
        'product_images',
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

    public function __construct(private readonly ExpressionLanguage $interpreter)
    {
    }

    public function applies(array $config): bool
    {
        return isset($config['type'])
            && \in_array($config['type'], self::$endpoints)
            && isset($config['method'])
            && 'get' === $config['method'];
    }

    public function getBuilder(array $config): Builder
    {
        $options = array_filter($config['options'] ?? []);

        if (isset($options['sorters']) && \is_array($options['sorters'])) {
            $options['sort'] = $this->compileSorters($options['sorters']);
            unset($options['sorters']);
        }

        if (isset($options['languages']) && \is_array($options['languages'])) {
            $options['language'] = $this->compileLanguages($options['languages']);
            unset($options['languages']);
        }

        if (isset($options['columns']) && \is_array($options['columns'])) {
            $options['display'] = $this->compileColumns($options['columns']);
            unset($options['columns']);
        }

        return (new Prestashop\Builder\Capacity\Get($this->interpreter, $options))
            ->withEndpoint(new Node\Identifier(\sprintf('get%sApi', ucfirst((string) $config['type']))))
        ;
    }

    private function compileSorters(array $sorters): string
    {
        $results = [];
        foreach ($sorters as $key => $value) {
            $results[] = $key.'_'.$value;
        }

        return \sprintf('[%s]', implode(',', $results));
    }

    private function compileLanguages(array $languages): string
    {
        if (\array_key_exists('from', $languages) && \array_key_exists('to', $languages)) {
            return \sprintf('[%s,%s]', $languages['from'], $languages['to']);
        }

        return \sprintf('[%s]', implode('|', $languages));
    }

    private function compileColumns(array $columns): string
    {
        return \sprintf('[%s]', implode(',', $columns));
    }
}
