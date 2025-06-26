<?php

declare(strict_types=1);

namespace Kiboko\Component\Dockerfile\PHP;

use Kiboko\Component\Dockerfile\Dockerfile;

final class ComposerInstall implements Dockerfile\LayerInterface, \Stringable
{
    public function __toString(): string
    {
        return (string) new Dockerfile\Run(
            <<<'RUN'
                set -ex \
                    && composer install --prefer-dist --no-progress --optimize-autoloader --no-dev
                RUN
        );
    }
}
