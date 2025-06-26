<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Sylius\Capacity;

use PhpParser\Builder;

interface CapacityInterface
{
    public function applies(array $config): bool;

    public function getBuilder(array $config): Builder;
}
