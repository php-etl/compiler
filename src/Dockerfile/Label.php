<?php

declare(strict_types=1);

namespace Kiboko\Component\Dockerfile\Dockerfile;

final readonly class Label implements LayerInterface, \Stringable
{
    public function __construct(private string $key, private string $value)
    {
    }

    public function __toString(): string
    {
        return sprintf('LABEL %s="%s"', $this->key, $this->value);
    }
}
