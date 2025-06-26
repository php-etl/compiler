<?php declare(strict_types=1);

namespace functional\Kiboko\Plugin\Prestashop\Builder\Capacity\Extractor;

use Kiboko\Component\PHPUnitExtension\Assert\ExtractorBuilderAssertTrait;
use Kiboko\Component\PHPUnitExtension\BuilderTestCase;
use Kiboko\Plugin\Prestashop\Builder\Client;
use Kiboko\Plugin\Prestashop\Builder\Extractor;
use Kiboko\Plugin\Prestashop\Capacity;
use PhpParser\Node;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

final class ExtractorTest extends BuilderTestCase
{
    use ExtractorBuilderAssertTrait;

    public function testAll()
    {
        $client = new Client(
            new Node\Scalar\String_('http://localhost:8001'),
            new Node\Scalar\String_('1VN86447KTXYCA51DGKYKJTGHDBE8T5Z')
        );

        $capacity = (new Capacity\All(new ExpressionLanguage()))->getBuilder([
            'type' => 'products',
        ]);

        $builder = new Extractor($capacity);

        $builder->withClient($client->getNode());

        $this->assertBuildsExtractorExtractsExactly([], $builder);
    }
}
