<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPUnitExtension\Assert;

use Kiboko\Component\PHPUnitExtension\Constraint\Pipeline\IteratesLike;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\Constraint\LogicalNot;

trait PipelineAssertTrait
{
    abstract public static function assertThat($value, Constraint $constraint, string $message = ''): void;

    protected function assertDoesIterateLike(iterable $expected, iterable $actual, $message = ''): void
    {
        static::assertThat($actual, new IteratesLike($expected, fn ($item) => new IsEqual($item)), $message);
    }

    protected function assertDoesIterateExactly(iterable $expected, iterable $actual, $message = ''): void
    {
        static::assertThat($actual, new IteratesLike($expected, fn ($item) => new IsIdentical($item)), $message);
    }

    protected function assertDoesNotIterateLike(iterable $expected, iterable $actual, $message = ''): void
    {
        static::assertThat($actual, new LogicalNot(
            new IteratesLike($expected, fn ($item) => new IsEqual($item)),
        ), $message);
    }

    protected function assertDoesNotIterateExactly(iterable $expected, iterable $actual, $message = ''): void
    {
        static::assertThat($actual, new LogicalNot(
            new IteratesLike($expected, fn ($item) => new IsIdentical($item)),
        ), $message);
    }
}
