<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPUnitExtension\Mock;

use PhpParser\Builder;
use PhpParser\Node;

final class RequestFactoryBuilder implements Builder
{
    public function getNode(): Node\Expr
    {
        return new Node\Expr\New_(
            class: new Node\Name\FullyQualified(RequestFactory::class)
        );
    }
}
