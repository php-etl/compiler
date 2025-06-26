<?php

namespace functional\Kiboko\Component\PHPUnitExtension\Constraint\Builder;

use PhpParser\Builder;
use PhpParser\Node;

class BuilderStub implements Builder
{
    public function getNode(): Node
    {
        return new Node\Expr\ConstFetch(new Node\Name('true'));
    }
}
