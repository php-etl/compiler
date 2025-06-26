<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPUnitExtension\Mock;

use PhpParser\Builder;
use PhpParser\Node;

final readonly class FileSystemBuilder implements Builder
{
    public function __construct(
        private string $mockedFilesystem,
    ) {}

    public function getNode(): Node\Expr
    {
        return new Node\Expr\New_(
            class: new Node\Name\FullyQualified($this->mockedFilesystem)
        );
    }
}
