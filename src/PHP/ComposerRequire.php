<?php

declare(strict_types=1);

namespace Kiboko\Component\Dockerfile\PHP;

use Kiboko\Component\Dockerfile\Dockerfile;

final class ComposerRequire implements Dockerfile\LayerInterface, \Stringable
{
    private readonly iterable $packages;

    public function __construct(string ...$packages)
    {
        $this->packages = $packages;
    }

    public function __toString(): string
    {
        return (string) new Dockerfile\Run(sprintf(<<<'RUN'
            set -ex \
                && composer require --prefer-dist --no-progress --prefer-stable --sort-packages --optimize-autoloader --with-dependencies %s
            RUN, implode(' ', array_map(fn (string $package) => escapeshellarg($package), $this->packages))));
    }
}
