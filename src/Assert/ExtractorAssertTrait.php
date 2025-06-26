<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPUnitExtension\Assert;

use Kiboko\Component\PHPUnitExtension\Constraint\Pipeline\PipelineExtractsLike;
use Kiboko\Contract\Pipeline\ExtractorInterface;
use Kiboko\Contract\Pipeline\PipelineRunnerInterface;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\Constraint\LogicalNot;

trait ExtractorAssertTrait
{
    abstract public static function assertThat($value, Constraint $constraint, string $message = ''): void;

    abstract public function pipelineRunner(): PipelineRunnerInterface;

    protected function assertExtractorExtractsLike(iterable $expected, ExtractorInterface $extractor, string $message = ''): void
    {
        static::assertThat($extractor, new PipelineExtractsLike($expected, fn ($item) => new IsEqual($item), $this->pipelineRunner()), $message);
    }

    protected function assertExtractorDoesNotExtractLike(iterable $expected, ExtractorInterface $extractor, string $message = ''): void
    {
        static::assertThat($extractor, new LogicalNot(
            new PipelineExtractsLike($expected, fn ($item) => new IsEqual($item), $this->pipelineRunner())
        ), $message);
    }

    protected function assertExtractorExtractsExactly(iterable $expected, ExtractorInterface $extractor, string $message = ''): void
    {
        static::assertThat($extractor, new PipelineExtractsLike($expected, fn ($item) => new IsIdentical($item), $this->pipelineRunner()), $message);
    }

    protected function assertExtractorDoesNotExtractExactly(iterable $expected, ExtractorInterface $extractor, string $message = ''): void
    {
        static::assertThat($extractor, new LogicalNot(
            new PipelineExtractsLike($expected, fn ($item) => new IsIdentical($item), $this->pipelineRunner())
        ), $message);
    }
}
