<?php

declare(strict_types=1);

namespace Kiboko\Component\Dockerfile\Dockerfile;

final readonly class CopyFrom implements LayerInterface, \Stringable
{
    public function __construct(
        private string $sourcePath,
        private string $destinationPath,
        private string $from,
    ) {
    }

    public function __toString(): string
    {
        return sprintf('COPY --from=%s %s %s', $this->from, $this->sourcePath, $this->destinationPath);
    }
}
