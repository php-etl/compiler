<?php

namespace functional\Kiboko\Component\PHPUnitExtension\Constraint\Builder;

use Kiboko\Component\PHPUnitExtension\Constraint\Builder\BuilderProducesCodeThat;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\Constraint\IsFalse;
use PHPUnit\Framework\Constraint\IsTrue;
use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;

class BuilderProducesCodeThatTest extends TestCase
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

    public function testEvaluateReturnTrue(): void
    {
        $constraint = new BuilderProducesCodeThat($this->fs->url(), new IsTrue());

        $this->assertTrue($constraint->evaluate(new BuilderStub(), returnResult: true));
    }

    public function testEvaluateReturnFalse(): void
    {
        $constraint = new BuilderProducesCodeThat($this->fs->url(), new IsFalse());

        $this->assertFalse($constraint->evaluate(new BuilderStub(), returnResult: true));
    }

    public function testCountIsOne(): void
    {
        $constraint = new BuilderProducesCodeThat($this->fs->url(), new IsTrue());

        $this->assertCount(1, $constraint);
    }
}
