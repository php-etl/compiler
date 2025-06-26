<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPUnitExtension;

use Kiboko\Contract\Pipeline\ExtractorInterface;
use Kiboko\Contract\Pipeline\LoaderInterface;
use Kiboko\Contract\Pipeline\TransformerInterface;

/** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\PipelineAssertTrait, Assert\ExtractorAssertTrait, Assert\TransformerAssertTrait and Assert\LoaderAssertTrait */
trait PipelineAssertTrait
{
    use Assert\ExtractorAssertTrait;
    use Assert\TransformerAssertTrait;
    use Assert\LoaderAssertTrait;
    use Assert\PipelineAssertTrait;

    /** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\ExtractorAssertTrait::assertExtractorExtractsLike */
    protected function assertPipelineExtractsLike(iterable $expected, ExtractorInterface $actual, $message = ''): void
    {
        @trigger_error(
            sprintf(
                'Since php-etl/phpunit-extension 0.2.0: The "%s" method is deprecated, use "%s::%s" instead.',
                __FUNCTION__,
                Assert\ExtractorAssertTrait::class,
                'assertExtractorExtractsLike',
            ),
            \E_USER_DEPRECATED
        );

        $this->assertExtractorExtractsLike($expected, $actual, $message);
    }

    /** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\ExtractorAssertTrait::assertExtractorDoesNotExtractLike */
    protected function assertPipelineNotExtractsLike(iterable $expected, ExtractorInterface $actual, $message = ''): void
    {
        @trigger_error(
            sprintf(
                'Since php-etl/phpunit-extension 0.2.0: The "%s" method is deprecated, use "%s::%s" instead.',
                __FUNCTION__,
                Assert\ExtractorAssertTrait::class,
                'assertExtractorDoesNotExtractLike',
            ),
            \E_USER_DEPRECATED
        );

        $this->assertExtractorDoesNotExtractLike($expected, $actual, $message);
    }

    /** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\ExtractorAssertTrait::assertExtractorExtractsExactly */
    protected function assertPipelineExtractsExactly(iterable $expected, ExtractorInterface $actual, $message = ''): void
    {
        @trigger_error(
            sprintf(
                'Since php-etl/phpunit-extension 0.2.0: The "%s" method is deprecated, use "%s::%s" instead.',
                __FUNCTION__,
                Assert\ExtractorAssertTrait::class,
                'assertExtractorExtractsExactly',
            ),
            \E_USER_DEPRECATED
        );

        $this->assertExtractorExtractsExactly($expected, $actual, $message);
    }

    /** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\ExtractorAssertTrait::assertExtractorDoesNotExtractExactly */
    protected function assertPipelineNotExtractsExactly(iterable $expected, ExtractorInterface $actual, $message = ''): void
    {
        @trigger_error(
            sprintf(
                'Since php-etl/phpunit-extension 0.2.0: The "%s" method is deprecated, use "%s::%s" instead.',
                __FUNCTION__,
                Assert\ExtractorAssertTrait::class,
                'assertExtractorDoesNotExtractExactly',
            ),
            \E_USER_DEPRECATED
        );

        $this->assertExtractorDoesNotExtractExactly($expected, $actual, $message);
    }

    /** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\TransformerAssertTrait::assertTransformerTransformsLike */
    protected function assertPipelineTransformsLike(iterable $expected, iterable $source, TransformerInterface $actual, $message = ''): void
    {
        @trigger_error(
            sprintf(
                'Since php-etl/phpunit-extension 0.2.0: The "%s" method is deprecated, use "%s::%s" instead.',
                __FUNCTION__,
                Assert\TransformerAssertTrait::class,
                'assertTransformerTransformsLike',
            ),
            \E_USER_DEPRECATED
        );

        $this->assertTransformerTransformsLike($expected, $source, $actual, $message);
    }

    /** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\TransformerAssertTrait::assertTransformerTransformsExactly */
    protected function assertPipelineTransformsExactly(iterable $expected, iterable $source, TransformerInterface $actual, $message = ''): void
    {
        @trigger_error(
            sprintf(
                'Since php-etl/phpunit-extension 0.2.0: The "%s" method is deprecated, use "%s::%s" instead.',
                __FUNCTION__,
                Assert\TransformerAssertTrait::class,
                'assertTransformerTransformsExactly',
            ),
            \E_USER_DEPRECATED
        );

        $this->assertTransformerTransformsExactly($expected, $source, $actual, $message);
    }

    /** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\TransformerAssertTrait::assertTransformerDoesNotTransformLike */
    protected function assertPipelineNotTransformsLike(iterable $expected, iterable $source, TransformerInterface $actual, $message = ''): void
    {
        @trigger_error(
            sprintf(
                'Since php-etl/phpunit-extension 0.2.0: The "%s" method is deprecated, use "%s::%s" instead.',
                __FUNCTION__,
                Assert\TransformerAssertTrait::class,
                'assertTransformerDoesNotTransformLike',
            ),
            \E_USER_DEPRECATED
        );

        $this->assertTransformerDoesNotTransformLike($expected, $source, $actual, $message);
    }

    /** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\TransformerAssertTrait::assertTransformerDoesNotTransformExactly */
    protected function assertPipelineNotTransformsExactly(iterable $expected, iterable $source, TransformerInterface $actual, $message = ''): void
    {
        @trigger_error(
            sprintf(
                'Since php-etl/phpunit-extension 0.2.0: The "%s" method is deprecated, use "%s::%s" instead.',
                __FUNCTION__,
                Assert\TransformerAssertTrait::class,
                'assertTransformerDoesNotTransformExactly',
            ),
            \E_USER_DEPRECATED
        );

        $this->assertTransformerDoesNotTransformExactly($expected, $source, $actual, $message);
    }

    /** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\LoaderAssertTrait::assertLoaderLoadsLike */
    protected function assertPipelineLoadsLike(iterable $expected, iterable $source, LoaderInterface $actual, $message = ''): void
    {
        @trigger_error(
            sprintf(
                'Since php-etl/phpunit-extension 0.2.0: The "%s" method is deprecated, use "%s::%s" instead.',
                __FUNCTION__,
                Assert\LoaderAssertTrait::class,
                'assertLoaderLoadsLike',
            ),
            \E_USER_DEPRECATED
        );

        $this->assertLoaderLoadsLike($expected, $source, $actual, $message);
    }

    /** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\LoaderAssertTrait::assertLoaderLoadsExactly */
    protected function assertPipelineLoadsExactly(iterable $expected, iterable $source, LoaderInterface $actual, $message = ''): void
    {
        @trigger_error(
            sprintf(
                'Since php-etl/phpunit-extension 0.2.0: The "%s" method is deprecated, use "%s::%s" instead.',
                __FUNCTION__,
                Assert\LoaderAssertTrait::class,
                'assertLoaderLoadsExactly',
            ),
            \E_USER_DEPRECATED
        );

        $this->assertLoaderLoadsExactly($expected, $source, $actual, $message);
    }

    /** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\LoaderAssertTrait::assertLoaderDoesNotLoadLike */
    protected function assertPipelineNotLoadsLike(iterable $expected, iterable $source, LoaderInterface $actual, $message = ''): void
    {
        @trigger_error(
            sprintf(
                'Since php-etl/phpunit-extension 0.2.0: The "%s" method is deprecated, use "%s::%s" instead.',
                __FUNCTION__,
                Assert\LoaderAssertTrait::class,
                'assertLoaderDoesNotLoadLike',
            ),
            \E_USER_DEPRECATED
        );

        $this->assertLoaderDoesNotLoadLike($expected, $source, $actual, $message);
    }

    /** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\LoaderAssertTrait::assertLoaderDoesNotLoadExactly */
    protected function assertPipelineNotLoadsExactly(iterable $expected, iterable $source, LoaderInterface $actual, $message = ''): void
    {
        @trigger_error(
            sprintf(
                'Since php-etl/phpunit-extension 0.2.0: The "%s" method is deprecated, use "%s::%s" instead.',
                __FUNCTION__,
                Assert\LoaderAssertTrait::class,
                'assertLoaderDoesNotLoadExactly',
            ),
            \E_USER_DEPRECATED
        );

        $this->assertLoaderDoesNotLoadExactly($expected, $source, $actual, $message);
    }

    /** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\LoaderAssertTrait::assertLoaderProducesFile */
    protected function assertPipelineWritesFile(string $expected, iterable $source, LoaderInterface $actual, $message = ''): void
    {
        @trigger_error(
            sprintf(
                'Since php-etl/phpunit-extension 0.2.0: The "%s" method is deprecated, use "%s::%s" instead.',
                __FUNCTION__,
                Assert\LoaderAssertTrait::class,
                'assertLoaderProducesFile',
            ),
            \E_USER_DEPRECATED
        );

        $this->assertLoaderProducesFile($expected, $source, $actual, $message);
    }

    /** @deprecated Since php-etl/phpunit-extension version 0.2, see Assert\LoaderAssertTrait::assertLoaderDoesNotProduceFile */
    protected function assertPipelineNotWritesFile(string $expected, iterable $source, LoaderInterface $actual, $message = ''): void
    {
        @trigger_error(
            sprintf(
                'Since php-etl/phpunit-extension 0.2.0: The "%s" method is deprecated, use "%s::%s" instead.',
                __FUNCTION__,
                Assert\LoaderAssertTrait::class,
                'assertLoaderDoesNotProduceFile',
            ),
            \E_USER_DEPRECATED
        );

        $this->assertLoaderDoesNotProduceFile($expected, $source, $actual, $message);
    }
}
