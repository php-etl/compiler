<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Prestashop\Builder\Capacity;

use PhpParser\Builder;
use PhpParser\Node;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

use function Kiboko\Component\SatelliteToolbox\Configuration\compileValueWhenExpression;

final class All implements Builder
{
    private Node\Expr|Node\Identifier|null $endpoint = null;

    public function __construct(
        private readonly ExpressionLanguage $interpreter,
        public array $options = [],
    ) {
    }

    public function withEndpoint(Node\Expr|Node\Identifier $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function getNode(): Node
    {
        if (null === $this->endpoint) {
            throw new \Exception(message: 'Please check your capacity builder, you should have selected an endpoint.');
        }

        $options = [];
        foreach ($this->options as $key => $value) {
            $options[] = new Node\Expr\ArrayItem(
                value: compileValueWhenExpression($this->interpreter, $value),
                key: compileValueWhenExpression($this->interpreter, $key),
            );
        }

        return new Node\Stmt\Expression(
            expr: new Node\Expr\Yield_(
                value: new Node\Expr\New_(
                    class: new Node\Name\FullyQualified(name: \Kiboko\Component\Bucket\AcceptanceResultBucket::class),
                    args: [
                        new Node\Arg(
                            value: new Node\Expr\MethodCall(
                                var: new Node\Expr\MethodCall(
                                    var: new Node\Expr\PropertyFetch(
                                        var: new Node\Expr\Variable('this'),
                                        name: new Node\Identifier('client')
                                    ),
                                    name: $this->endpoint
                                ),
                                name: new Node\Identifier('all'),
                                args: [
                                    new Node\Arg(new Node\Expr\Array_($options)),
                                ]
                            ),
                            unpack: true,
                        ),
                    ],
                ),
            ),
        );
    }
}
