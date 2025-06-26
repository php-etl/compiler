<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPUnitExtension\Assert;

use Kiboko\Component\PHPUnitExtension\Constraint\Pipeline\PipelineLoadsLike;
use Kiboko\Component\PHPUnitExtension\Constraint\Pipeline\PipelineWritesFile;
use Kiboko\Contract\Pipeline\LoaderInterface;
use Kiboko\Contract\Pipeline\PipelineRunnerInterface;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\Constraint\LogicalNot;

trait LoaderAssertTrait
{
    abstract public static function assertThat($value, Constraint $constraint, string $message = ''): void;

    abstract public function pipelineRunner(): PipelineRunnerInterface;

    protected function assertLoaderLoadsLike(iterable $expected, iterable $input, LoaderInterface $loader, string $message = ''): void
    {
        static::assertThat($loader, new PipelineLoadsLike($expected, $input, fn ($item) => new IsEqual($item), $this->pipelineRunner()), $message);
    }

    protected function assertLoaderDoesNotLoadLike(iterable $expected, iterable $input, LoaderInterface $loader, string $message = ''): void
    {
        static::assertThat($loader, new LogicalNot(
            new PipelineLoadsLike($expected, $input, fn ($item) => new IsEqual($item), $this->pipelineRunner())
        ), $message);
    }

    protected function assertLoaderLoadsExactly(iterable $expected, iterable $input, LoaderInterface $loader, string $message = ''): void
    {
        static::assertThat($loader, new PipelineLoadsLike($expected, $input, fn ($item) => new IsIdentical($item), $this->pipelineRunner()), $message);
    }

    protected function assertLoaderDoesNotLoadExactly(iterable $expected, iterable $input, LoaderInterface $loader, string $message = ''): void
    {
        static::assertThat($loader, new LogicalNot(
            new PipelineLoadsLike($expected, $input, fn ($item) => new IsIdentical($item), $this->pipelineRunner())
        ), $message);
    }

    protected function assertLoaderProducesFile(string $expected, iterable $input, LoaderInterface $loader, string $message = ''): void
    {
        static::assertThat($loader, new PipelineWritesFile($input, $expected, $this->pipelineRunner()), $message);
    }

    protected function assertLoaderDoesNotProduceFile(string $expected, iterable $input, LoaderInterface $loader, string $message = ''): void
    {
        static::assertThat($loader, new LogicalNot(
            new PipelineWritesFile($input, $expected, $this->pipelineRunner()),
        ), $message);
    }
}
