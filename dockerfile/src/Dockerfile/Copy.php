<?php

declare(strict_types=1);

namespace Kiboko\Component\Dockerfile\Dockerfile;

final readonly class Copy implements LayerInterface, \Stringable
{
    public function __construct(private string $source, private string $destination)
    {
    }

    /** @return \Iterator|self[] */
    public static function directory(string $sourcePath, string $destinationPath): \Iterator
    {
        $iterator = new \RecursiveDirectoryIterator(
            $sourcePath,
            \RecursiveDirectoryIterator::SKIP_DOTS
            | \RecursiveDirectoryIterator::FOLLOW_SYMLINKS
            | \RecursiveDirectoryIterator::CURRENT_AS_FILEINFO
            | \RecursiveDirectoryIterator::KEY_AS_PATHNAME | \FilesystemIterator::SKIP_DOTS
        );

        /** @var \SplFileInfo $fileInfo */
        foreach (new \RecursiveIteratorIterator($iterator) as $fileInfo) {
            if (!$fileInfo->isFile()) {
                continue;
            }

            yield new self(
                preg_replace('/^'.preg_quote($sourcePath, '/').'/', '', $fileInfo->getPathname()),
                preg_replace('/^'.preg_quote($sourcePath, '/').'/', $destinationPath, $fileInfo->getPathname()),
            );
        }
    }

    /**
     * @param \Iterator<array<string,string>> $iterator
     */
    public static function iterator(\Iterator $iterator): \Iterator
    {
        /**
         * @var string $sourcePath
         * @var string $destinationPath
         */
        foreach ($iterator as [$sourcePath, $destinationPath]) {
            yield new self($sourcePath, $destinationPath);
        }
    }

    public function __toString(): string
    {
        return sprintf('COPY %s %s', $this->source, $this->destination);
    }
}
