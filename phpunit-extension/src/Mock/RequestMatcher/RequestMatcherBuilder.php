<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPUnitExtension\Mock\RequestMatcher;

use Http\Message\RequestMatcher;
use PhpParser\Node;

final readonly class RequestMatcherBuilder implements RequestMatcherBuilderInterface
{
    /**
     * @param list<string> $methods
     * @param list<string> $schemes
     */
    public function __construct(
        private ?string $path = null,
        private ?string $host = null,
        private array $methods = [],
        private array $schemes = []
    ) {}

    public function getNode(): Node\Expr
    {
        return new Node\Expr\New_(
            class: new Node\Name\FullyQualified(RequestMatcher::class),
            args: [
                new Node\Arg(
                    value: null !== $this->path ? new Node\Scalar\String_($this->path) : new Node\Expr\ConstFetch(new Node\Name('null')),
                    name: new Node\Identifier('path'),
                ),
                new Node\Arg(
                    value: null !== $this->host ? new Node\Scalar\String_($this->host) : new Node\Expr\ConstFetch(new Node\Name('null')),
                    name: new Node\Identifier('host'),
                ),
                new Node\Arg(
                    value: new Node\Expr\Array_(
                        items: array_map(
                            fn (string $value): Node\Expr => new Node\Expr\ArrayItem(
                                new Node\Scalar\String_($value),
                            ),
                            $this->methods
                        ),
                        attributes: [
                            'kind' => Node\Expr\Array_::KIND_SHORT,
                        ],
                    ),
                    name: new Node\Identifier('methods'),
                ),
                new Node\Arg(
                    value: new Node\Expr\Array_(
                        items: array_map(
                            fn (string $value): Node\Expr => new Node\Expr\ArrayItem(
                                new Node\Scalar\String_($value),
                            ),
                            $this->schemes
                        ),
                        attributes: [
                            'kind' => Node\Expr\Array_::KIND_SHORT,
                        ],
                    ),
                    name: new Node\Identifier('schemes'),
                ),
            ],
        );
    }
}
