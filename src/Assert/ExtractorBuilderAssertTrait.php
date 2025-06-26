<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPUnitExtension\Assert;

use Kiboko\Component\PHPUnitExtension\Constraint\Builder\BuilderProducesCodeThat;
use Kiboko\Component\PHPUnitExtension\Constraint\Pipeline\PipelineExtractsLike;
use Kiboko\Contract\Pipeline\PipelineRunnerInterface;
use PhpParser\Builder as DefaultBuilder;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\Constraint\LogicalNot;

trait ExtractorBuilderAssertTrait
{
    use BuilderAssertTrait;

    abstract public static function assertThat($value, Constraint $constraint, string $message = ''): void;

    abstract public function pipelineRunner(): PipelineRunnerInterface;

    protected function assertBuildsExtractorExtractsLike(iterable $expected, DefaultBuilder $builder, string $message = ''): void
    {
        static::assertThat($builder, new BuilderProducesCodeThat(
            $this->getBuilderCompilePath(),
            new PipelineExtractsLike($expected, fn ($item) => new IsEqual($item), $this->pipelineRunner())
        ), $message);
    }

    protected function assertBuildsExtractorDoesNotExtractLike(iterable $expected, DefaultBuilder $builder, string $message = ''): void
    {
        static::assertThat($builder, new LogicalNot(
            new BuilderProducesCodeThat(
                $this->getBuilderCompilePath(),
                new PipelineExtractsLike($expected, fn ($item) => new IsEqual($item), $this->pipelineRunner())
            ),
        ), $message);
    }

    protected function assertBuildsExtractorExtractsExactly(iterable $expected, DefaultBuilder $builder, string $message = ''): void
    {
        static::assertThat($builder, new BuilderProducesCodeThat(
            $this->getBuilderCompilePath(),
            new PipelineExtractsLike($expected, fn ($item) => new IsIdentical($item), $this->pipelineRunner())
        ), $message);
    }

    protected function assertBuildsExtractorDoesNotExtractExactly(iterable $expected, DefaultBuilder $builder, string $message = ''): void
    {
        static::assertThat($builder, new LogicalNot(
            new BuilderProducesCodeThat(
                $this->getBuilderCompilePath(),
                new PipelineExtractsLike($expected, fn ($item) => new IsIdentical($item), $this->pipelineRunner())
            ),
        ), $message);
    }
}
