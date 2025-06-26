<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPUnitExtension\Constraint\Pipeline;

use PHPUnit\Framework\Constraint\Constraint;

/** @template Type */
final class IteratesLike extends Constraint
{
    /** @var callable */
    private $itemConstraintFactory;

    /** @param list<Type> $expected */
    public function __construct(
        private readonly iterable $expected,
        callable $itemConstraintFactory,
    ) {
        $this->itemConstraintFactory = $itemConstraintFactory;
    }

    /**
     * @param list<Type> $iterable
     *
     * @return \Iterator<Type>
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

    public function matches(mixed $other): bool
    {
        $both = new \MultipleIterator(\MultipleIterator::MIT_NEED_ANY);

        $both->attachIterator($this->asIterator($this->expected));
        $both->attachIterator($iterator = $this->asIterator($other));

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
            '%s iterates like %s',
            $this->exporter()->export($this->exporter()->toArray($other)),
            $this->exporter()->export($this->exporter()->toArray($this->expected)),
        );
    }

    public function toString(): string
    {
        return 'iterates like';
    }
}
