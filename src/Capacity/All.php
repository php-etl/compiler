<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Sylius\Capacity;

use Kiboko\Plugin\Sylius;
use PhpParser\Builder;
use PhpParser\Node;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

use function Kiboko\Component\SatelliteToolbox\Configuration\compileValue;

final class All implements CapacityInterface
{
    private static array $endpoints = [
        // Simple resources Endpoints
        'channels',
        'countries',
        'carts',
        'channels',
        'countries',
        'currencies',
        'customers',
        'exchangeRates',
        'locales',
        'orders',
        'payments',
        'paymentMethods',
        'products',
        'productAttributes',
        'productAssociationTypes',
        'productOptions',
        'promotions',
        'shipments',
        'shippingCategories',
        'taxCategories',
        'taxRates',
        'taxons',
        'users',
        'zones',
    ];

    private static array $doubleEndpoints = [
        // Double resources Endpoints
        'productReviews',
        'productVariants',
        'promotionCoupons',
    ];

    public function __construct(private readonly ExpressionLanguage $interpreter)
    {
    }

    public function applies(array $config): bool
    {
        return isset($config['type'])
            && (\in_array($config['type'], self::$endpoints) || \in_array($config['type'], self::$doubleEndpoints))
            && isset($config['method'])
            && 'all' === $config['method'];
    }

    private function compileFilters(array ...$filters): Node
    {
        $builder = new Sylius\Builder\Search();
        foreach ($filters as $filter) {
            $builder->addFilter(
                field: compileValue($this->interpreter, $filter['field']),
                operator: compileValue($this->interpreter, $filter['operator']),
                value: compileValue($this->interpreter, $filter['value']),
                scope: \array_key_exists('scope', $filter) ? compileValue($this->interpreter, $filter['scope']) : null,
                locale: \array_key_exists('locale', $filter) ? compileValue($this->interpreter, $filter['locale']) : null
            );
        }

        return $builder->getNode();
    }

    public function getBuilder(array $config): Builder
    {
        $builder = (new Sylius\Builder\Capacity\All())
            ->withEndpoint(new Node\Identifier(sprintf('get%sApi', ucfirst((string) $config['type']))))
        ;

        if (isset($config['search']) && \is_array($config['search'])) {
            $builder->withSearch($this->compileFilters(...$config['search']));
        }

        return $builder;
    }
}
