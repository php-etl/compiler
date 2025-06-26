<?php

declare(strict_types=1);

namespace Kiboko\Component\Dockerfile\PHP;

use Kiboko\Component\Dockerfile\Dockerfile;

final readonly class ComposerAddGithubRepository implements Dockerfile\LayerInterface, \Stringable
{
    public function __construct(
        private string $name,
        private string $url
    ) {
    }

    public function __toString(): string
    {
        return (string) new Dockerfile\Run(sprintf(<<<'RUN'
            set -ex \
                && composer config repositories.%s github %s
            RUN, $this->name, $this->url));
    }
}
