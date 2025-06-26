<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPUnitExtension\Constraint\Pipeline;

use Kiboko\Contract\Bucket\ResultBucketInterface;
use Kiboko\Contract\Pipeline\ExtractorInterface;
use Kiboko\Contract\Pipeline\NullStepRejection;
use Kiboko\Contract\Pipeline\NullStepState;
use Kiboko\Contract\Pipeline\PipelineRunnerInterface;
use PHPUnit\Framework\Constraint\Constraint;

/** @template Type */
final class PipelineExtractsLike extends Constraint
{
    /** @var callable */
    private $itemConstraintFactory;

    /** @param list<Type> $expected */
    public function __construct(
        private readonly iterable $expected,
        callable $itemConstraintFactory,
        private readonly PipelineRunnerInterface $runner,
    ) {
        $this->itemConstraintFactory = $itemConstraintFactory;
    }

    /**
     * @template ItemType
     *
     * @param list<ItemType> $iterable
     *
     * @return \Iterator<ItemType>
     */
    private function asIterator(iterable $iterable): \Iterator
    {
        if (\is_array($iterable)) {
            return new \ArrayIterator($iterable);
        }
        if ($iterable instanceof \Iterator) {
            return $iterable;
        }

        return new \IteratorIterator($iterable);
    }

    /** @return \Generator<mixed, Type|null, Type|null, void> */
    private function passThroughCoroutine(): \Generator
    {
        $line = yield;
        while ($line = yield $line) {
        }
    }

    public function matches(mixed $other): bool
    {
        $both = new \MultipleIterator(\MultipleIterator::MIT_NEED_ANY);

        $both->attachIterator($this->asIterator($this->expected));

        if (!$other instanceof ExtractorInterface) {
            $this->fail($other, strtr('Expected an instance of %expected%, but got %actual%.', [
                '%expected%' => ExtractorInterface::class,
                '%actual%' => get_debug_type($other),
            ]));
        }

        $extract = $other->extract();
        $iterator = $this->runner->run(
            $this->asIterator($extract),
            $this->passThroughCoroutine(),
            new NullStepRejection(),
            new NullStepState(),
        );
        $both->attachIterator($iterator);

        $index = 0;
        foreach ($both as [$expectedItem, $actualItem]) {
            ++$index;
            $constraint = ($this->itemConstraintFactory)($expectedItem);
            true !== $constraint->evaluate($actualItem, sprintf('Values of Iteration #%d', $index));
        }

        return !$iterator->valid();
    }

    protected function failureDescription(mixed $other): string
    {
        return sprintf(
            '%s pipeline extracts like %s',
            $this->exporter()->export($this->exporter()->toArray($other)),
            $this->exporter()->export($this->exporter()->toArray($this->expected)),
        );
    }

    public function toString(): string
    {
        return 'pipeline extracts like';
    }
}
