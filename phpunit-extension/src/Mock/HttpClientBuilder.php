<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPUnitExtension\Mock;

use Http\Mock\Client;
use Kiboko\Component\PHPUnitExtension\Mock;
use PhpParser\Builder;
use PhpParser\Node;

final class HttpClientBuilder implements Builder
{
    /** @var Node\Expr[] */
    private array $nodes;

    public function __construct(
        private readonly Mock\ResponseFactoryBuilder $responseFactory
    ) {}

    public function expectResponse(
        Mock\RequestMatcher\RequestMatcherBuilderInterface $requestMatcher,
        Mock\ResponseBuilder $response
    ): self {
        $this->nodes[] = new Node\Expr\MethodCall(
            var: new Node\Expr\Variable('client'),
            name: new Node\Identifier('on'),
            args: [
                new Node\Arg(
                    value: $requestMatcher->getNode(),
                ),
                new Node\Arg(
                    value: $response->getNode(),
                ),
            ],
        );

        return $this;
    }

    public function expectException(
        Mock\RequestMatcher\RequestMatcherBuilderInterface $requestMatcher,
        Mock\ExceptionBuilder $exception,
    ): self {
        $this->nodes[] = new Node\Expr\MethodCall(
            var: new Node\Expr\Variable('client'),
            name: new Node\Identifier('on'),
            args: [
                new Node\Arg(
                    value: $requestMatcher->getNode(),
                ),
                new Node\Arg(
                    value: $exception->getNode(),
                ),
            ],
        );

        return $this;
    }

    public function getNode(): Node\Expr
    {
        return new Node\Expr\FuncCall(
            name: new Node\Expr\Closure(
                subNodes: [
                    'stmts' => [
                        new Node\Stmt\Expression(
                            new Node\Expr\Assign(
                                var: new Node\Expr\Variable('client'),
                                expr: new Node\Expr\New_(
                                    class: new Node\Name\FullyQualified(
                                        name: Client::class
                                    ),
                                    args: [
                                        new Node\Arg(
                                            $this->responseFactory->getNode(),
                                        ),
                                    ],
                                ),
                            ),
                        ),
                        ...array_map(
                            fn (Node\Expr $node) => new Node\Stmt\Expression($node),
                            $this->nodes,
                        ),
                        new Node\Stmt\Return_(
                            expr: new Node\Expr\Variable('client'),
                        ),
                    ],
                ],
            ),
        );
    }
}
