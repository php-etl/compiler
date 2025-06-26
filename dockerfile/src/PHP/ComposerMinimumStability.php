<?php

declare(strict_types=1);

namespace Kiboko\Component\Dockerfile\PHP;

use Kiboko\Component\Dockerfile\Dockerfile;

final readonly class ComposerMinimumStability implements Dockerfile\LayerInterface, \Stringable
{
    public function __construct(private string $minimumStability)
    {
    }

    public function __toString(): string
    {
        return (string) new Dockerfile\Run(sprintf(<<<'RUN'
            set -ex \
                && composer config minimum-stability %s
            RUN, $this->minimumStability));
    }
}
