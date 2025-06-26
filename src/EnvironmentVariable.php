<?php

declare(strict_types=1);

namespace Kiboko\Component\Dockerfile;

final readonly class EnvironmentVariable implements Variable
{
    public function __construct(
        public string $name,
    ) {
    }

    public function __toString()
    {
        return "\${{$this->name}}";
    }
}
