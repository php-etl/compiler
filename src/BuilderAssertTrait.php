<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPUnitExtension;

use Kiboko\Component\PHPUnitExtension\Constraint\Builder\BuilderProducesCodeThat;
use Kiboko\Component\PHPUnitExtension\Constraint\Pipeline\IteratesLike;
use PhpParser\Builder as DefaultBuilder;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\Constraint\LogicalNot;

/** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\PipelineBuilderAssertTrait, Assert\ExtractorBuilderAssertTrait, Assert\TransformerBuilderAssertTrait and Assert\LoaderBuilderAssertTrait */
trait BuilderAssertTrait
{
    use Assert\ExtractorBuilderAssertTrait;
    use Assert\TransformerBuilderAssertTrait;
    use Assert\LoaderBuilderAssertTrait;
    use Assert\PipelineBuilderAssertTrait;

    /** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\ExtractorBuilderAssertTrait::assertBuildsExtractorExtractsLike() */
    protected function assertBuilderProducesExtractorIteratesAs(array $expected, DefaultBuilder $builder, string $message = ''): void
    {
        @trigger_error(
            sprintf(
                'Since php-etl/phpunit-extension 0.2.0: The "%s" method is deprecated, use "%s::%s" instead.',
                __FUNCTION__,
                Assert\ExtractorBuilderAssertTrait::class,
                'assertBuildsExtractorExtractsLike',
            ),
            \E_USER_DEPRECATED
        );

        $this->assertBuildsExtractorExtractsLike($expected, $builder, $message);
    }

    /** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\ExtractorBuilderAssertTrait::assertBuildsExtractorDoesNotExtractLike() */
    protected function assertBuilderProducesExtractorNotIteratesAs(array $expected, DefaultBuilder $builder, string $message = ''): void
    {
        @trigger_error(
            sprintf(
                'Since php-etl/phpunit-extension 0.2.0: The "%s" method is deprecated, use "%s::%s" instead.',
                __FUNCTION__,
                Assert\ExtractorBuilderAssertTrait::class,
                'assertBuildsExtractorDoesNotExtractLike',
            ),
            \E_USER_DEPRECATED
        );

        $this->assertBuildsExtractorDoesNotExtractLike($expected, $builder, $message);
    }

    /** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\ExtractorBuilderAssertTrait::assertBuildsExtractorExtractsLike() */
    protected function assertBuilderProducesPipelineExtractingLike(iterable $expected, DefaultBuilder $builder, string $message = ''): void
    {
        @trigger_error(
            sprintf(
                'Since php-etl/phpunit-extension 0.2.0: The "%s" method is deprecated, use "%s::%s" instead.',
                __FUNCTION__,
                Assert\ExtractorBuilderAssertTrait::class,
                'assertBuildsExtractorExtractsLike',
            ),
            \E_USER_DEPRECATED
        );

        static::assertThat($builder, new BuilderProducesCodeThat(
            new IteratesLike($expected, fn ($item) => new IsEqual($item))
        ), $message);
    }

    /** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\ExtractorBuilderAssertTrait::assertBuildsExtractorExtractsExactly() */
    protected function assertBuilderProducesPipelineExtractingExactly(iterable $expected, DefaultBuilder $builder, string $message = ''): void
    {
        @trigger_error(
            sprintf(
                'Since php-etl/phpunit-extension 0.2.0: The "%s" method is deprecated, use "%s::%s" instead.',
                __FUNCTION__,
                Assert\ExtractorBuilderAssertTrait::class,
                'assertBuildsExtractorExtractsExactly',
            ),
            \E_USER_DEPRECATED
        );

        static::assertThat($builder, new BuilderProducesCodeThat(
            new IteratesLike($expected, fn ($item) => new IsIdentical($item))
        ), $message);
    }

    /** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\ExtractorBuilderAssertTrait::assertBuildsExtractorDoesNotExtractLike() */
    protected function assertBuilderProducesPipelineNotExtractingLike(iterable $expected, DefaultBuilder $builder, string $message = ''): void
    {
        @trigger_error(
            sprintf(
                'Since php-etl/phpunit-extension 0.2.0: The "%s" method is deprecated, use "%s::%s" instead.',
                __FUNCTION__,
                Assert\ExtractorBuilderAssertTrait::class,
                'assertBuildsExtractorDoesNotExtractLike',
            ),
            \E_USER_DEPRECATED
        );

        static::assertThat($builder, new LogicalNot(
            new BuilderProducesCodeThat(
                new IteratesLike($expected, fn ($item) => new IsEqual($item))
            ),
        ), $message);
    }

    /** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\ExtractorBuilderAssertTrait::assertBuildsExtractorDoesNotExtractExactly() */
    protected function assertBuilderProducesPipelineNotExtractingExactly(iterable $expected, DefaultBuilder $builder, string $message = ''): void
    {
        @trigger_error(
            sprintf(
                'Since php-etl/phpunit-extension 0.2.0: The "%s" method is deprecated, use "%s::%s" instead.',
                __FUNCTION__,
                Assert\ExtractorBuilderAssertTrait::class,
                'assertBuildsExtractorDoesNotExtractExactly',
            ),
            \E_USER_DEPRECATED
        );

        static::assertThat($builder, new LogicalNot(
            new BuilderProducesCodeThat(
                new IteratesLike($expected, fn ($item) => new IsIdentical($item))
            ),
        ), $message);
    }

    /** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\TransformerBuilderAssertTrait::assertBuildsTransformerTransformsLike */
    protected function assertBuilderProducesPipelineTransformingLike(iterable $expected, iterable $actual, DefaultBuilder $builder, string $message = ''): void
    {
        @trigger_error(
            sprintf(
                'Since php-etl/phpunit-extension 0.2.0: The "%s" method is deprecated, use "%s::%s" instead.',
                __FUNCTION__,
                Assert\TransformerBuilderAssertTrait::class,
                'assertBuildsTransformerTransformsLike',
            ),
            \E_USER_DEPRECATED
        );

        $this->assertBuildsTransformerTransformsLike($expected, $actual, $builder, $message);
    }

    /** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\TransformerBuilderAssertTrait::assertBuildsTransformerTransformsExactly */
    protected function assertBuilderProducesPipelineTransformingExactly(iterable $expected, iterable $actual, DefaultBuilder $builder, string $message = ''): void
    {
        @trigger_error(
            sprintf(
                'Since php-etl/phpunit-extension 0.2.0: The "%s" method is deprecated, use "%s::%s" instead.',
                __FUNCTION__,
                Assert\TransformerBuilderAssertTrait::class,
                'assertBuildsTransformerTransformsExactly',
            ),
            \E_USER_DEPRECATED
        );

        $this->assertBuildsTransformerTransformsExactly($expected, $actual, $builder, $message);
    }

    /** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\TransformerBuilderAssertTrait::assertBuildsTransformerDoesNotTransformLike */
    protected function assertBuilderProducesPipelineNotTransformingLike(iterable $expected, iterable $actual, DefaultBuilder $builder, string $message = ''): void
    {
        @trigger_error(
            sprintf(
                'Since php-etl/phpunit-extension 0.2.0: The "%s" method is deprecated, use "%s::%s" instead.',
                __FUNCTION__,
                Assert\TransformerBuilderAssertTrait::class,
                'assertBuildsTransformerDoesNotTransformLike',
            ),
            \E_USER_DEPRECATED
        );

        $this->assertBuildsTransformerDoesNotTransformLike($expected, $actual, $builder, $message);
    }

    /** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\TransformerBuilderAssertTrait::assertBuildsTransformerDoesNotTransformExactly */
    protected function assertBuilderProducesPipelineNotTransformingExactly(iterable $expected, iterable $actual, DefaultBuilder $builder, string $message = ''): void
    {
        @trigger_error(
            sprintf(
                'Since php-etl/phpunit-extension 0.2.0: The "%s" method is deprecated, use "%s::%s" instead.',
                __FUNCTION__,
                Assert\TransformerBuilderAssertTrait::class,
                'assertBuildsTransformerDoesNotTransformExactly',
            ),
            \E_USER_DEPRECATED
        );

        $this->assertBuildsTransformerDoesNotTransformExactly($expected, $actual, $builder, $message);
    }

    /** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\LoaderBuilderAssertTrait::assertBuildsLoaderLoadsLike */
    protected function assertBuilderProducesPipelineLoadingLike(iterable $expected, iterable $actual, DefaultBuilder $builder, string $message = ''): void
    {
        @trigger_error(
            sprintf(
                'Since php-etl/phpunit-extension 0.2.0: The "%s" method is deprecated, use "%s::%s" instead.',
                __FUNCTION__,
                Assert\LoaderBuilderAssertTrait::class,
                'assertBuildsLoaderLoadsLike',
            ),
            \E_USER_DEPRECATED
        );

        $this->assertBuildsLoaderLoadsLike($expected, $actual, $builder, $message);
    }

    /** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\LoaderBuilderAssertTrait::assertBuildsLoaderLoadsExactly */
    protected function assertBuilderProducesPipelineLoadingExactly(iterable $expected, iterable $actual, DefaultBuilder $builder, string $message = ''): void
    {
        @trigger_error(
            sprintf(
                'Since php-etl/phpunit-extension 0.2.0: The "%s" method is deprecated, use "%s::%s" instead.',
                __FUNCTION__,
                Assert\LoaderBuilderAssertTrait::class,
                'assertBuildsLoaderLoadsExactly',
            ),
            \E_USER_DEPRECATED
        );

        $this->assertBuildsLoaderLoadsExactly($expected, $actual, $builder, $message);
    }

    /** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\LoaderBuilderAssertTrait::assertBuildsLoaderDoesNotLoadLike */
    protected function assertBuilderProducesPipelineNotLoadingLike(iterable $expected, iterable $actual, DefaultBuilder $builder, string $message = ''): void
    {
        @trigger_error(
            sprintf(
                'Since php-etl/phpunit-extension 0.2.0: The "%s" method is deprecated, use "%s::%s" instead.',
                __FUNCTION__,
                Assert\LoaderBuilderAssertTrait::class,
                'assertBuildsLoaderDoesNotLoadLike',
            ),
            \E_USER_DEPRECATED
        );

        $this->assertBuildsLoaderDoesNotLoadLike($expected, $actual, $builder, $message);
    }

    /** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\LoaderBuilderAssertTrait::assertBuildsLoaderDoesNotLoadExactly */
    protected function assertBuilderProducesPipelineNotLoadingExactly(iterable $expected, iterable $actual, DefaultBuilder $builder, string $message = ''): void
    {
        @trigger_error(
            sprintf(
                'Since php-etl/phpunit-extension 0.2.0: The "%s" method is deprecated, use "%s::%s" instead.',
                __FUNCTION__,
                Assert\LoaderBuilderAssertTrait::class,
                'assertBuildsLoaderDoesNotLoadExactly',
            ),
            \E_USER_DEPRECATED
        );

        $this->assertBuildsLoaderDoesNotLoadExactly($expected, $actual, $builder, $message);
    }

    /** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\LoaderBuilderAssertTrait::assertBuildsLoaderProducesFile */
    protected function assertBuilderProducesLoaderWritingFile(string $expected, array $source, DefaultBuilder $builder, string $message = ''): void
    {
        @trigger_error(
            sprintf(
                'Since php-etl/phpunit-extension 0.2.0: The "%s" method is deprecated, use "%s::%s" instead.',
                __FUNCTION__,
                Assert\LoaderBuilderAssertTrait::class,
                'assertBuildsLoaderProducesFile',
            ),
            \E_USER_DEPRECATED
        );

        $this->assertBuildsLoaderProducesFile($expected, $source, $builder, $message);
    }

    /** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\LoaderBuilderAssertTrait::assertBuildsLoaderDoesNotProduceFile */
    protected function assertBuilderProducesLoaderNotWritingFile(string $expected, array $source, DefaultBuilder $builder, string $message = ''): void
    {
        @trigger_error(
            sprintf(
                'Since php-etl/phpunit-extension 0.2.0: The "%s" method is deprecated, use "%s::%s" instead.',
                __FUNCTION__,
                Assert\LoaderBuilderAssertTrait::class,
                'assertBuildsExtractorIteratingLike',
            ),
            \E_USER_DEPRECATED
        );

        $this->assertBuildsLoaderDoesNotProduceFile($expected, $source, $builder, $message);
    }
}
