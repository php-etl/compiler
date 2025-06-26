<?php

declare(strict_types=1);

namespace Kiboko\Plugin\CSV\Builder;

use Kiboko\Contract\Configurator\StepBuilderInterface;
use PhpParser\Node;

final class Loader implements StepBuilderInterface
{
    private ?Node\Expr $logger = null;

    public function __construct(private Node\Expr $filePath, private ?Node\Expr $delimiter = null, private ?Node\Expr $enclosure = null, private ?Node\Expr $escape = null, private ?Node\Expr $columns = null, private bool $safeMode = true, private bool $withNonStandard = false) {}

    public function withFilePath(Node\Expr $filePath): self
    {
        $this->filePath = $filePath;

        return $this;
    }

    public function withDelimiter(Node\Expr $delimiter): self
    {
        $this->delimiter = $delimiter;

        return $this;
    }

    public function withEnclosure(Node\Expr $enclosure): self
    {
        $this->enclosure = $enclosure;

        return $this;
    }

    public function withEscape(Node\Expr $escape): self
    {
        $this->escape = $escape;

        return $this;
    }

    public function withColumns(Node\Expr $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    public function withLogger(Node\Expr $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    public function withRejection(Node\Expr $rejection): self
    {
        return $this;
    }

    public function withState(Node\Expr $state): self
    {
        return $this;
    }

    public function withSafeMode(): self
    {
        $this->safeMode = true;

        return $this;
    }

    public function withFingersCrossedMode(): self
    {
        $this->safeMode = false;

        return $this;
    }

    public function withNonStandard(): self
    {
        $this->withNonStandard = true;

        return $this;
    }

    public function getNode(): Node
    {
        $arguments = [
            new Node\Arg(
                value: new Node\Expr\New_(
                    class: true === $this->withNonStandard ?
                        new Node\Stmt\Class_(
                            name: 'SplFileObject',
                            subNodes: [
                                'extends' => new Node\Name\FullyQualified('SplFileObject'),
                                'stmts' => [
                                    new Node\Stmt\ClassMethod(
                                        name: 'fputcsv',
                                        subNodes: [
                                            'type' => Node\Stmt\Class_::MODIFIER_PUBLIC,
                                            'params' => [
                                                new Node\Param(
                                                    var: new Node\Expr\Variable('fields'),
                                                    type: 'array',
                                                ),
                                                new Node\Param(
                                                    var: new Node\Expr\Variable('separator'),
                                                    default: new Node\Scalar\String_(','),
                                                    type: 'string',
                                                ),
                                                new Node\Param(
                                                    var: new Node\Expr\Variable('enclosure'),
                                                    default: new Node\Scalar\String_('"'),
                                                    type: 'string',
                                                ),
                                                new Node\Param(
                                                    var: new Node\Expr\Variable('escape'),
                                                    default: new Node\Scalar\String_('\\'),
                                                    type: 'string',
                                                ),
                                                new Node\Param(
                                                    var: new Node\Expr\Variable('eol'),
                                                    default: new Node\Expr\ConstFetch(new Node\Name('PHP_EOL')),
                                                    type: 'string',
                                                ),
                                            ],
                                            'returnType' => new Node\UnionType(
                                                types: [
                                                    new Node\Name('int'),
                                                    new Node\Name('false'),
                                                ],
                                            ),
                                            'stmts' => [
                                                new Node\Stmt\Return_(
                                                    expr: new Node\Expr\MethodCall(
                                                        var: new Node\Expr\Variable('this'),
                                                        name: 'fwrite',
                                                        args: [
                                                            new Node\Arg(
                                                                value: new Node\Expr\BinaryOp\Concat(
                                                                    left: new Node\Expr\FuncCall(
                                                                        name: new Node\Name('implode'),
                                                                        args: [
                                                                            new Node\Arg(
                                                                                value: new Node\Expr\Variable('separator')
                                                                            ),
                                                                            new Node\Arg(
                                                                                value: new Node\Expr\Variable('fields')
                                                                            ),
                                                                        ],
                                                                    ),
                                                                    right: new Node\Expr\Variable('eol')
                                                                ),
                                                            ),
                                                        ],
                                                    ),
                                                ),
                                            ],
                                        ],
                                    ),
                                ],
                            ],
                        ) : new Node\Name\FullyQualified('SplFileObject'),
                    args: [
                        new Node\Arg($this->filePath),
                        new Node\Arg(new Node\Scalar\String_('w')),
                    ],
                ),
                name: new Node\Identifier('file'),
            ),
        ];

        if (null !== $this->delimiter) {
            $arguments[] = new Node\Arg(
                value: $this->delimiter,
                name: new Node\Identifier('delimiter'),
            );
        }

        if (null !== $this->enclosure) {
            $arguments[] = new Node\Arg(
                value: $this->enclosure,
                name: new Node\Identifier('enclosure'),
            );
        }

        if (null !== $this->escape) {
            $arguments[] = new Node\Arg(
                value: $this->escape,
                name: new Node\Identifier('escape'),
            );
        }

        if (null !== $this->columns) {
            $arguments[] = new Node\Arg(
                value: $this->columns,
                name: new Node\Identifier('columns'),
            );
        }

        if (null !== $this->logger) {
            $arguments[] = new Node\Arg(
                value: $this->logger,
                name: new Node\Identifier('logger'),
            );
        }

        return new Node\Expr\New_(
            class: new Node\Name\FullyQualified(
                $this->safeMode
                    ? \Kiboko\Component\Flow\Csv\Safe\Loader::class
                    : \Kiboko\Component\Flow\Csv\FingersCrossed\Loader::class
            ),
            args: $arguments,
        );
    }
}
