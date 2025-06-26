<?php

declare(strict_types=1);

namespace Kiboko\Component\Dockerfile\PHP;

use Kiboko\Component\Dockerfile\Dockerfile;

final readonly class ComposerInit implements Dockerfile\LayerInterface, \Stringable
{
    public function __construct(private string $name)
    {
    }

    public function __toString(): string
    {
        return (string) new Dockerfile\Run(sprintf(
            <<<'RUN'
                set -ex \
                    && composer init --no-interaction --name=%s \
                    && composer config use-github-api false
                RUN,
            $this->name
        ));
    }
}
