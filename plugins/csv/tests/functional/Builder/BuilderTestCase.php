<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\CSV\Builder;

use functional\Kiboko\Plugin\CSV;
use Kiboko\Component\PHPUnitExtension\Assert\PipelineBuilderAssertTrait;
use Kiboko\Contract\Pipeline\PipelineRunnerInterface;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;
use PHPUnit\Framework\TestCase;

abstract class BuilderTestCase extends TestCase
{
    use PipelineBuilderAssertTrait;

    private ?vfsStreamDirectory $fs = null;
    protected function setUp(): void
    {
        $this->fs = vfsStream::setup();
    }
    protected function tearDown(): void
    {
        $this->fs = null;
        vfsStreamWrapper::unregister();
    }

    public function pipelineRunner(): PipelineRunnerInterface
    {
        return new CSV\PipelineRunner();
    }
}
