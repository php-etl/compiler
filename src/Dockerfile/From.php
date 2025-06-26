<?php

declare(strict_types=1);

namespace Kiboko\Component\Dockerfile\Dockerfile;

final readonly class From implements LayerInterface, \Stringable
{
    public function __construct(
        private string $source,
        private ?string $alias = null,
    ) {
    }

    public function __toString(): string
    {
        if ($this->alias === null) {
            return sprintf('FROM %s', $this->source);
        }

        return sprintf('FROM %s AS %s', $this->source, $this->alias);
    }
}
