<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPUnitExtension\Mock;

use PhpParser\Builder;
use PhpParser\Node;

final readonly class ResponseBuilder implements Builder
{
    public function __construct(
        private string $path,
    ) {}

    public function getNode(): Node\Expr
    {
        return new Node\Expr\Include_(new Node\Scalar\String_($this->path), Node\Expr\Include_::TYPE_INCLUDE);
    }
}
