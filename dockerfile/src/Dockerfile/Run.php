<?php

declare(strict_types=1);

namespace Kiboko\Component\Dockerfile\Dockerfile;

final readonly class Run implements LayerInterface, \Stringable
{
    public function __construct(private string $command)
    {
    }

    public function __toString(): string
    {
        return sprintf('RUN %s', $this->command);
    }
}
