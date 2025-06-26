<?php

namespace functional\Kiboko\Plugin\JSON\Builder;

use Kiboko\Component\PHPUnitExtension\BuilderAssertTrait;
use Kiboko\Plugin\JSON\Builder\Loader;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;
use PHPUnit\Framework\TestCase;

abstract class LoaderTestCase extends TestCase
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
        $loader = new Loader(filePath: 'vfs://output.jsonld');
        $this->assertBuilderProducesInstanceOf('Kiboko\Component\Flow\JSON\Loader', $loader);
        $this->assertBuilderProducesPipelineLoadingLike([['firstname' => 'john', 'lastname' => 'doe'], ['firstname' => 'jean', 'lastname' => 'dupont']], [['firstname' => 'john', 'lastname' => 'doe'], ['firstname' => 'jean', 'lastname' => 'dupont']], $loader);
    }
    public function testWritingFile()
    {
        $loader = new Loader(filePath: 'vfs://output.jsonld');
        $this->assertBuilderProducesLoaderWritingFile(__DIR__ . '/../files/source-to-extract.jsonld', [[['firstname' => 'john', 'lastname' => 'doe'], ['firstname' => 'jean', 'lastname' => 'dupont']], [['firstname' => 'john', 'lastname' => 'doe'], ['firstname' => 'jean', 'lastname' => 'dupont']]], $loader);
    }
}
