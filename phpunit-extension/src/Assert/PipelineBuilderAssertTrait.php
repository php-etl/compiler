<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPUnitExtension\Assert;

use Kiboko\Component\PHPUnitExtension\Constraint\Builder\BuilderProducesCodeThat;
use PhpParser\Builder as DefaultBuilder;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsInstanceOf;
use PHPUnit\Framework\Constraint\LogicalNot;

trait PipelineBuilderAssertTrait
{
    use BuilderAssertTrait;

    abstract public static function assertThat($value, Constraint $constraint, string $message = ''): void;

    protected function assertBuilderProducesInstanceOf(string $expected, DefaultBuilder $builder, string $message = ''): void
    {
        static::assertThat($builder, new BuilderProducesCodeThat(
            $this->getBuilderCompilePath(),
            new IsInstanceOf($expected)
        ), $message);
    }

    protected function assertBuilderProducesNotInstanceOf(string $expected, DefaultBuilder $builder, string $message = ''): void
    {
        static::assertThat($builder, new LogicalNot(
            new BuilderProducesCodeThat(
                $this->getBuilderCompilePath(),
                new IsInstanceOf($expected)
            ),
        ), $message);
    }
}
