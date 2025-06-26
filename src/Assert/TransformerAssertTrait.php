<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPUnitExtension\Assert;

use Kiboko\Component\PHPUnitExtension\Constraint\Pipeline\PipelineTransformsLike;
use Kiboko\Contract\Pipeline\PipelineRunnerInterface;
use Kiboko\Contract\Pipeline\TransformerInterface;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\Constraint\LogicalNot;

trait TransformerAssertTrait
{
    abstract public static function assertThat($value, Constraint $constraint, string $message = ''): void;

    abstract public function pipelineRunner(): PipelineRunnerInterface;

    protected function assertTransformerTransformsLike(iterable $expected, iterable $input, TransformerInterface $transformer, string $message = ''): void
    {
        static::assertThat($transformer, new PipelineTransformsLike($expected, $input, fn ($item) => new IsEqual($item), $this->pipelineRunner()), $message);
    }

    protected function assertTransformerDoesNotTransformLike(iterable $expected, iterable $input, TransformerInterface $transformer, string $message = ''): void
    {
        static::assertThat($transformer, new LogicalNot(
            new PipelineTransformsLike($expected, $input, fn ($item) => new IsEqual($item), $this->pipelineRunner())
        ), $message);
    }

    protected function assertTransformerTransformsExactly(iterable $expected, iterable $input, TransformerInterface $transformer, string $message = ''): void
    {
        static::assertThat($transformer, new PipelineTransformsLike($expected, $input, fn ($item) => new IsIdentical($item), $this->pipelineRunner()), $message);
    }

    protected function assertTransformerDoesNotTransformExactly(iterable $expected, iterable $input, TransformerInterface $transformer, string $message = ''): void
    {
        static::assertThat($transformer, new LogicalNot(
            new PipelineTransformsLike($expected, $input, fn ($item) => new IsIdentical($item), $this->pipelineRunner())
        ), $message);
    }
}
