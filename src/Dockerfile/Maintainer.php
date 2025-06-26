<?php

declare(strict_types=1);

namespace Kiboko\Component\Dockerfile\Dockerfile;

final readonly class Maintainer implements LayerInterface, \Stringable
{
    public function __construct(private string $name, private string $email)
    {
    }

    public function __toString(): string
    {
        return sprintf('LABEL maintainer="%s <%s>"', $this->name, $this->email);
    }
}
