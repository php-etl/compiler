<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Shopify\Factory\Repository;

use Kiboko\Contract\Configurator;
use Kiboko\Plugin\Shopify;

final class Loader implements Configurator\StepRepositoryInterface
{
    use RepositoryTrait;

    public function __construct(private readonly Akeneo\Builder\Loader $builder)
    {
        $this->files = [];
        $this->packages = [];
    }

    public function getBuilder(): Akeneo\Builder\Loader
    {
        return $this->builder;
    }

    public function merge(Configurator\RepositoryInterface $friend): self
    {
        array_push($this->files, ...$friend->getFiles());
        array_push($this->packages, ...$friend->getPackages());

        return $this;
    }
}
