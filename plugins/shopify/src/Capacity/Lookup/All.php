<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Shopify\Capacity\Lookup;

use Kiboko\Contract\Configurator\InvalidConfigurationException;
use Kiboko\Plugin\Shopify;
use PhpParser\Builder;
use PhpParser\Node;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

use function Kiboko\Component\SatelliteToolbox\Configuration\compileValueWhenExpression;

final class All implements Akeneo\Capacity\CapacityInterface
{
    private static array $endpoints = [
        // Core Endpoints
        'product',
        'category',
        'attribute',
        'attributeOption',
        'attributeGroup',
        'family',
        'productMediaFile',
        'locale',
        'channel',
        'currency',
        'measureFamily',
        'associationType',
        'familyVariant',
        'productModel',
        // Enterprise Endpoints
        'publishedProduct',
        'productModelDraft',
        'productDraft',
        'asset',
        'assetCategory',
        'assetTag',
        'referenceEntityRecord',
        'referenceEntityAttribute',
        'referenceEntityAttributeOption',
        'referenceEntity',
        'assetManager',
    ];

    private static array $unaryOperators = [
        'EMPTY',
        'NOT EMPTY',
        'AT LEAST COMPLETE',
        'AT LEAST INCOMPLETE',
        'ALL COMPLETE',
        'ALL INCOMPLETE',
        'UNCLASSIFIED',
    ];

    public function __construct(private readonly ExpressionLanguage $interpreter)
    {
    }

    public function applies(array $config): bool
    {
        return isset($config['type'])
            && \in_array($config['type'], self::$endpoints)
            && isset($config['method'])
            && 'all' === $config['method'];
    }

    private function compileFilters(array ...$filters): Node\Expr
    {
        $builder = new Akeneo\Builder\Search();
        foreach ($filters as $filter) {
            if (\in_array($filter['operator'], self::$unaryOperators, true) && \array_key_exists('value', $filter)) {
                throw new InvalidConfigurationException(\sprintf('You should not provide a value for the %s operator', $filter['operator']));
            }
            if (!\in_array($filter['operator'], self::$unaryOperators, true) && !\array_key_exists('value', $filter)) {
                throw new InvalidConfigurationException(\sprintf('You should provide a value for the %s operator', $filter['operator']));
            }

            $builder->addFilter(
                field: compileValueWhenExpression($this->interpreter, $filter['field']),
                operator: compileValueWhenExpression($this->interpreter, $filter['operator']),
                value: \array_key_exists('value', $filter) ? compileValueWhenExpression($this->interpreter, $filter['value']) : null,
                scope: \array_key_exists('scope', $filter) ? compileValueWhenExpression($this->interpreter, $filter['scope']) : null,
                locale: \array_key_exists('locale', $filter) ? compileValueWhenExpression($this->interpreter, $filter['locale']) : null
            );
        }

        return $builder->getNode();
    }

    public function getBuilder(array $config): Builder
    {
        $builder = (new Akeneo\Builder\Capacity\Lookup\All())
            ->withType((string) $config['type'])
            ->withEndpoint(new Node\Identifier(\sprintf('get%sApi', ucfirst((string) $config['type']))))
        ;

        if (isset($config['search']) && \is_array($config['search'])) {
            $builder->withSearch($this->compileFilters(...$config['search']));
        }

        if (\in_array($config['type'], ['attributeOption', 'assetManager', 'referenceEntityRecord']) && \array_key_exists('code', $config)) {
            $builder->withCode(compileValueWhenExpression($this->interpreter, $config['code']));
        }

        return $builder;
    }
}
