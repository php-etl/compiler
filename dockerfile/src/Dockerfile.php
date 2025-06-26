<?php

declare(strict_types=1);

namespace Kiboko\Component\Dockerfile;

use Kiboko\Component\Dockerfile\Dockerfile\LayerInterface;
use Kiboko\Contract\Packaging\FileInterface;

final class Dockerfile implements \IteratorAggregate, \Countable, FileInterface, \Stringable
{
    /** @var iterable|Dockerfile\LayerInterface[] */
    private iterable $layers;

    public function __construct(null|LayerInterface ...$layers)
    {
        $this->layers = $layers;
    }

    public function push(LayerInterface ...$layers): void
    {
        array_push($this->layers, ...$layers);
    }

    public function __toString(): string
    {
        return implode(\PHP_EOL, array_map(fn (LayerInterface $layer) => (string) $layer.\PHP_EOL, $this->layers));
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->layers);
    }

    public function count(): int
    {
        return \count($this->layers);
    }

    public function asResource()
    {
        $resource = fopen('php://temp', 'rb+');
        fwrite($resource, (string) $this);
        fseek($resource, 0, \SEEK_SET);

        return $resource;
    }

    public function getPath(): string
    {
        return 'Dockerfile';
    }
}
