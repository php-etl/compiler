<?php

declare(strict_types=1);

namespace Kiboko\Plugin\JSON\Builder;

use Kiboko\Contract\Configurator\StepBuilderInterface;
use PhpParser\Node;

final readonly class Extractor implements StepBuilderInterface
{
    public function __construct(private string $filePath)
    {
    }

    public function withLogger(Node\Expr $logger): StepBuilderInterface
    {
        return $this;
    }

    public function withRejection(Node\Expr $rejection): StepBuilderInterface
    {
        return $this;
    }

    public function withState(Node\Expr $state): StepBuilderInterface
    {
        return $this;
    }

    public function getNode(): Node
    {
        $arguments = [
            new Node\Arg(
                new Node\Expr\New_(
                    class: new Node\Name\FullyQualified('SplFileObject'),
                    args: [
                        new Node\Arg(
                            new Node\Scalar\String_($this->filePath)
                        ),
                        new Node\Arg(
                            new Node\Scalar\String_('r')
                        ),
                    ]
                ),
                name: new Node\Identifier('file')
            ),
        ];

        return new Node\Expr\New_(
            class: new Node\Name\FullyQualified('Kiboko\\Component\\Flow\\JSON\\Extractor'),
            args: $arguments
        );
    }
}
