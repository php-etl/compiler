<?php

declare(strict_types=1);

namespace Kiboko\Component\Dockerfile\PHP;

use Kiboko\Component\Dockerfile\Dockerfile;
use Kiboko\Component\Dockerfile\Variable;

final readonly class ComposerGlobalConfig implements Dockerfile\LayerInterface, \Stringable
{
    public function __construct(
        private string $key,
        private string|Variable $value,
    ) {
    }

    public function __toString(): string
    {
        return (string) new Dockerfile\Run(<<<"RUN"
            composer config --global {$this->key} {$this->value}
            RUN
        );
    }
}
