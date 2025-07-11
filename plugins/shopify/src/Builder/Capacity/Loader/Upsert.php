<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Shopify\Builder\Capacity\Loader;

use Kiboko\Plugin\Shopify\MissingEndpointException;
use Kiboko\Plugin\Shopify\MissingParameterException;
use PhpParser\Builder;
use PhpParser\Node;

final class Upsert implements Builder
{
    private Node\Expr|Node\Identifier|null $endpoint = null;
    private ?Node\Expr $code = null;
    private ?Node\Expr $data = null;
    private ?Node\Expr $referenceEntity = null;
    private ?Node\Expr $referenceEntityAttribute = null;

    public function withEndpoint(Node\Expr|Node\Identifier $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function withCode(Node\Expr $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function withData(Node\Expr $line): self
    {
        $this->data = $line;

        return $this;
    }

    public function withReferenceEntity(Node\Expr $referenceEntity): self
    {
        $this->referenceEntity = $referenceEntity;

        return $this;
    }

    public function withReferenceEntityAttribute(Node\Expr $referenceEntityAttribute): self
    {
        $this->referenceEntityAttribute = $referenceEntityAttribute;

        return $this;
    }

    public function getNode(): Node
    {
        if (null === $this->endpoint) {
            throw new MissingEndpointException(message: 'Please check your capacity builder, you should have selected an endpoint.');
        }
        if (null === $this->code) {
            throw new MissingParameterException(message: 'Please check your capacity builder, you should have provided a code.');
        }
        if (null === $this->data) {
            throw new MissingParameterException(message: 'Please check your capacity builder, you should have provided some data.');
        }

        return new Node\Stmt\While_(
            cond: new Node\Expr\ConstFetch(
                name: new Node\Name('true')
            ),
            stmts: [
                new Node\Stmt\TryCatch(
                    stmts: [
                        new Node\Stmt\Expression(
                            expr: new Node\Expr\MethodCall(
                                new Node\Expr\MethodCall(
                                    var: new Node\Expr\PropertyFetch(
                                        var: new Node\Expr\Variable('this'),
                                        name: new Node\Identifier('client'),
                                    ),
                                    name: $this->endpoint,
                                ),
                                new Node\Identifier('upsert'),
                                array_filter([
                                    $this->referenceEntity ? new Node\Arg(value: $this->referenceEntity) : null,
                                    $this->referenceEntityAttribute ? new Node\Arg(value: $this->referenceEntityAttribute) : null,
                                    new Node\Arg(value: $this->code),
                                    new Node\Arg(value: $this->data),
                                ]),
                            ),
                        ),
                        new Node\Stmt\Expression(
                            expr: new Node\Expr\Assign(
                                var: new Node\Expr\Variable('line'),
                                expr: new Node\Expr\Yield_(
                                    value: new Node\Expr\New_(
                                        class: new Node\Name\FullyQualified(name: \Kiboko\Component\Bucket\AcceptanceResultBucket::class),
                                        args: [
                                            new Node\Arg(
                                                value: new Node\Expr\Variable('line'),
                                            ),
                                        ],
                                    ),
                                ),
                            )
                        ),
                    ],
                    catches: [
                        new Node\Stmt\Catch_(
                            types: [
                                new Node\Name\FullyQualified(
                                    name: \Akeneo\Pim\ApiClient\Exception\UnprocessableEntityHttpException::class
                                ),
                            ],
                            var: new Node\Expr\Variable('exception'),
                            stmts: [
                                new Node\Stmt\Expression(
                                    expr: new Node\Expr\MethodCall(
                                        var: new Node\Expr\PropertyFetch(
                                            var: new Node\Expr\Variable('this'),
                                            name: 'logger',
                                        ),
                                        name: new Node\Identifier('error'),
                                        args: [
                                            new Node\Arg(
                                                value: new Node\Expr\MethodCall(
                                                    var: new Node\Expr\Variable('exception'),
                                                    name: new Node\Identifier('getMessage'),
                                                ),
                                            ),
                                            new Node\Arg(
                                                value: new Node\Expr\Array_(
                                                    items: [
                                                        new Node\Expr\ArrayItem(
                                                            value: new Node\Expr\Variable('exception'),
                                                            key: new Node\Scalar\String_('exception'),
                                                        ),
                                                        new Node\Expr\ArrayItem(
                                                            value: new Node\Expr\MethodCall(
                                                                var: new Node\Expr\Variable('exception'),
                                                                name: new Node\Identifier('getResponseErrors'),
                                                            ),
                                                            key: new Node\Scalar\String_('errors'),
                                                        ),
                                                        new Node\Expr\ArrayItem(
                                                            value: new Node\Expr\Variable('line'),
                                                            key: new Node\Scalar\String_('item'),
                                                        ),
                                                    ],
                                                    attributes: [
                                                        'kind' => Node\Expr\Array_::KIND_SHORT,
                                                    ],
                                                ),
                                            ),
                                        ],
                                    ),
                                ),
                                new Node\Stmt\Expression(
                                    expr: new Node\Expr\Assign(
                                        var: new Node\Expr\Variable('line'),
                                        expr: new Node\Expr\Yield_(
                                            value: new Node\Expr\New_(
                                                class: new Node\Name\FullyQualified(
                                                    name: \Kiboko\Component\Bucket\RejectionResultBucket::class
                                                ),
                                                args: [
                                                    new Node\Arg(
                                                        value: new Node\Expr\MethodCall(
                                                            var: new Node\Expr\Variable('exception'),
                                                            name: new Node\Identifier('getMessage'),
                                                        ),
                                                    ),
                                                    new Node\Arg(
                                                        value: new Node\Expr\Variable('exception'),
                                                    ),
                                                    new Node\Arg(
                                                        value: new Node\Expr\Variable('line'),
                                                    ),
                                                ],
                                            ),
                                        ),
                                    ),
                                ),
                            ],
                        ),
                        new Node\Stmt\Catch_(
                            types: [
                                new Node\Name\FullyQualified(
                                    name: \Akeneo\Pim\ApiClient\Exception\HttpException::class,
                                ),
                            ],
                            var: new Node\Expr\Variable('exception'),
                            stmts: [
                                new Node\Stmt\Expression(
                                    expr: new Node\Expr\MethodCall(
                                        var: new Node\Expr\PropertyFetch(
                                            var: new Node\Expr\Variable('this'),
                                            name: 'logger',
                                        ),
                                        name: new Node\Identifier('error'),
                                        args: [
                                            new Node\Arg(
                                                value: new Node\Expr\MethodCall(
                                                    var: new Node\Expr\Variable('exception'),
                                                    name: new Node\Identifier('getMessage'),
                                                ),
                                            ),
                                            new Node\Arg(
                                                value: new Node\Expr\Array_(
                                                    items: [
                                                        new Node\Expr\ArrayItem(
                                                            value: new Node\Expr\Variable('exception'),
                                                            key: new Node\Scalar\String_('exception'),
                                                        ),
                                                        new Node\Expr\ArrayItem(
                                                            value: new Node\Expr\Variable('line'),
                                                            key: new Node\Scalar\String_('item'),
                                                        ),
                                                    ],
                                                    attributes: [
                                                        'kind' => Node\Expr\Array_::KIND_SHORT,
                                                    ],
                                                ),
                                            ),
                                        ],
                                    ),
                                ),
                                new Node\Stmt\Expression(
                                    expr: new Node\Expr\Assign(
                                        var: new Node\Expr\Variable('line'),
                                        expr: new Node\Expr\Yield_(
                                            value: new Node\Expr\New_(
                                                class: new Node\Name\FullyQualified(
                                                    name: \Kiboko\Component\Bucket\RejectionResultBucket::class
                                                ),
                                                args: [
                                                    new Node\Arg(
                                                        value: new Node\Expr\MethodCall(
                                                            var: new Node\Expr\Variable('exception'),
                                                            name: new Node\Identifier('getMessage'),
                                                        ),
                                                    ),
                                                    new Node\Arg(
                                                        value: new Node\Expr\Variable('exception'),
                                                    ),
                                                    new Node\Arg(
                                                        value: new Node\Expr\Variable('line'),
                                                    ),
                                                ],
                                            ),
                                        ),
                                    ),
                                ),
                            ],
                        ),
                    ],
                ),
            ],
        );
    }
}
