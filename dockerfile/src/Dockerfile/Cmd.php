<?php

declare(strict_types=1);

namespace Kiboko\Component\Dockerfile\Dockerfile;

final class Cmd implements LayerInterface, \Stringable
{
    private readonly iterable $command;

    public function __construct(string ...$command)
    {
        $this->command = $command;
    }

    public function __toString(): string
    {
        return sprintf('CMD [%s]', implode(', ', $this->command));
    }
}
