<?php

declare(strict_types=1);

namespace functional\Kiboko\Plugin\Sylius\Builder;

use Http\Mock\Client;
use Kiboko\Component\PHPUnitExtension\Assert\PipelineBuilderAssertTrait;
use Kiboko\Component\PHPUnitExtension\BuilderTestCase;
use Kiboko\Component\PHPUnitExtension\Mock\ResponseFactory;
use Kiboko\Plugin\Sylius\Builder;
use Kiboko\Plugin\Sylius\MissingAuthenticationMethodException;
use PhpParser\Node;

final class ClientTest extends BuilderTestCase
{
    use PipelineBuilderAssertTrait;

    private function getClientNode(): Node\Expr
    {
        return new Node\Expr\New_(
            class: new Node\Name\FullyQualified(Client::class),
            args: [
                new Node\Arg(
                    new Node\Expr\New_(
                        new Node\Name\FullyQualified(ResponseFactory::class)
                    ),
                ),
            ],
        );
    }

    public function testExpectingTokenOrPassword(): void
    {
        $client = new Builder\Client(
            new Node\Scalar\String_('http://demo.akeneo.com'),
            new Node\Scalar\String_(''),
            new Node\Scalar\String_(''),
        );

        $this->expectException(MissingAuthenticationMethodException::class);
        $this->expectExceptionMessage('Please check your client builder, you should either call withToken() or withPassword() methods.');

        $client->getNode();
    }
}
