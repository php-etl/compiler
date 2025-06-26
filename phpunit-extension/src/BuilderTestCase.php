<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPUnitExtension;

use Kiboko\Contract\Pipeline\PipelineRunnerInterface;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

abstract class BuilderTestCase extends TestCase
{
    private ?vfsStreamDirectory $fs = null;

    protected function setUp(): void
    {
        $this->fs = vfsStream::setup();
    }

    protected function tearDown(): void
    {
        $this->fs = null;
    }

    protected function getBuilderCompilePath(): string
    {
        if ($this->fs === null) {
            throw new \RuntimeException('The virtual file system was not initialized. The '.__METHOD__.' method should be called after the '.static::class.'::setUp() method was called and after the '.static::class.'::tearDown() method is called.');
        }

        return $this->fs->url();
    }

    public function pipelineRunner(): PipelineRunnerInterface
    {
        return new PipelineRunner();
    }
}
