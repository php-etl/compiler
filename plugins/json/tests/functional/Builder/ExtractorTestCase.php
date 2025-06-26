<?php

namespace functional\Kiboko\Plugin\JSON\Builder;

use Kiboko\Component\PHPUnitExtension\BuilderAssertTrait;
use Kiboko\Plugin\JSON\Builder\Extractor;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;

abstract class ExtractorTestCase extends \PHPUnit\Framework\TestCase
{
    use BuilderAssertTrait;
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
    public function testWithoutOption()
    {
        $extractor = new Extractor(filePath: __DIR__ . '/../files/source-to-extract.jsonld');
        $this->assertBuilderProducesInstanceOf('Kiboko\Component\Flow\JSON\Extractor', $extractor);
        $this->assertBuilderProducesExtractorIteratesAs([['firstname' => 'john', 'lastname' => 'doe'], ['firstname' => 'jean', 'lastname' => 'dupont']], $extractor);
    }
}
