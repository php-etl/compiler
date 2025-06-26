<?php

declare(strict_types=1);

namespace Kiboko\Plugin\JSON\Factory\Repository;

use Kiboko\Contract\Configurator;
use Kiboko\Plugin\JSON;

final class Loader implements Configurator\StepRepositoryInterface
{
    use RepositoryTrait;

    public function __construct(private readonly JSON\Builder\Loader $builder)
    {
        $this->files = [];
        $this->packages = [];
    }

    public function getBuilder(): JSON\Builder\Loader
    {
        return $this->builder;
    }
}
