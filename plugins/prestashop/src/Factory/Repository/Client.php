<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Prestashop\Factory\Repository;

use Kiboko\Contract\Configurator;
use Kiboko\Plugin\Prestashop;

final class Client implements Configurator\RepositoryInterface
{
    use RepositoryTrait;

    public function __construct(private readonly Prestashop\Builder\Client $builder)
    {
        $this->files = [];
        $this->packages = [];
    }

    public function getBuilder(): Prestashop\Builder\Client
    {
        return $this->builder;
    }
}
