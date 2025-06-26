<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPUnitExtension\Mock\RequestMatcher;

use PhpParser\Builder;
use PhpParser\Node;

interface RequestMatcherBuilderInterface extends Builder
{
    public function getNode(): Node\Expr;
}
