<?php

declare(strict_types=1);

namespace Kiboko\Component\Dockerfile\Dockerfile;

final readonly class Arg implements LayerInterface, \Stringable
{
    public function __construct(
        private string $name,
        private ?string $defaultValue = null,
    ) {
    }

    public function __toString(): string
    {
        if (null !== $this->defaultValue) {
            return sprintf('ARG %s=%s', $this->name, $this->defaultValue);
        }

        return sprintf('ARG %s', $this->name);
    }
}
