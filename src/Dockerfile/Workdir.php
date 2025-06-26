<?php

declare(strict_types=1);

namespace Kiboko\Component\Dockerfile\Dockerfile;

final readonly class Workdir implements LayerInterface, \Stringable
{
    public function __construct(private string $path)
    {
    }

    public function __toString(): string
    {
        return sprintf('WORKDIR %s', $this->path);
    }
}
