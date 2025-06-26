<?php

declare(strict_types=1);

namespace Kiboko\Plugin\CSV\Factory\Repository;

use Kiboko\Contract\Configurator;
use Kiboko\Plugin\CSV;

final class Extractor implements Configurator\StepRepositoryInterface
{
    use RepositoryTrait;

    public function __construct(private readonly CSV\Builder\Extractor $builder)
    {
        $this->files = [];
        $this->packages = [];
    }

    public function getBuilder(): CSV\Builder\Extractor
    {
        return $this->builder;
    }
}
