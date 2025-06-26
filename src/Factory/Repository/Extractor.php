<?php

declare(strict_types=1);

namespace Kiboko\Plugin\JSON\Factory\Repository;

use Kiboko\Contract\Configurator;
use Kiboko\Plugin\JSON;

final class Extractor implements Configurator\StepRepositoryInterface
{
    use RepositoryTrait;

    public function __construct(private readonly JSON\Builder\Extractor $builder)
    {
        $this->files = [];
        $this->packages = [];
    }

    public function getBuilder(): JSON\Builder\Extractor
    {
        return $this->builder;
    }
}
