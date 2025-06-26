<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Prestashop\Factory\Repository;

use Kiboko\Contract\Configurator;
use Kiboko\Plugin\Prestashop;

final class Loader implements Configurator\StepRepositoryInterface
{
    use RepositoryTrait;

    public function __construct(private readonly Prestashop\Builder\Loader $builder)
    {
        $this->files = [];
        $this->packages = [];
    }

    public function getBuilder(): Prestashop\Builder\Loader
    {
        return $this->builder;
    }
}
