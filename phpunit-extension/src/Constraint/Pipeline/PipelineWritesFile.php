<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPUnitExtension\Constraint\Pipeline;

use Kiboko\Contract\Pipeline\FlushableInterface;
use Kiboko\Contract\Pipeline\LoaderInterface;
use Kiboko\Contract\Pipeline\NullStepRejection;
use Kiboko\Contract\Pipeline\NullStepState;
use Kiboko\Contract\Pipeline\PipelineRunnerInterface;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\FileExists;

/** @template Type */
final class PipelineWritesFile extends Constraint
{
    /**
     * @param list<Type> $source
     */
    public function __construct(
        private readonly iterable $source,
        private readonly string $expected,
        private readonly PipelineRunnerInterface $runner,
    ) {}

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
        if (!$other instanceof LoaderInterface) {
            $this->fail($other, strtr('Expected an instance of %expected%, but got %actual%.', [
                '%expected%' => LoaderInterface::class,
                '%actual%' => get_debug_type($other),
            ]));
        }

        if ($other instanceof FlushableInterface) {
            $iterator = new \AppendIterator();

            $iterator->append(
                $this->runner->run(
                    $this->asIterator($this->source),
                    $other->load(),
                    new NullStepRejection(),
                    new NullStepState(),
                )
            );
            $iterator->append(
                $this->runner->run(
                    new \ArrayIterator([[]]),
                    (function () use ($other): \Generator {
                        yield;
                        yield $other->flush();
                    })(),
                    new NullStepRejection(),
                    new NullStepState(),
                )
            );
        } else {
            $iterator = $this->runner->run(
                $this->asIterator($this->source),
                $other->load(),
                new NullStepRejection(),
                new NullStepState(),
            );
        }

        $constraint = new FileExists();
        $constraint->evaluate($this->expected);

        return !$iterator->valid();
    }

    protected function failureDescription(mixed $other): string
    {
        return sprintf(
            '%s pipeline writes file %s',
            $this->exporter()->export($this->exporter()->toArray($other)),
            $this->exporter()->export($this->exporter()->toArray($this->expected)),
        );
    }

    public function toString(): string
    {
        return 'pipeline writes file';
    }
}
