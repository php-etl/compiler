<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPUnitExtension\Assert;

trait BuilderAssertTrait
{
    abstract protected function getBuilderCompilePath(): string;
}
