<?php

declare(strict_types=1);

namespace Kiboko\Component\PHPUnitExtension\Mock;

use Faker\Factory;
use Kiboko\Component\PHPUnitExtension\Mock;
use PhpParser\Builder;
use PhpParser\Node;

/** @deprecated This should be moved to php-etl/akeneo-plugin as it is very specific to Akeneo plugin */
final class ApiClientMockBuilder implements Builder
{
    private Node\Expr $node;

    /** @param array<Node\Arg|Node\VariadicPlaceholder> $mockedArgs */
    public function __construct(
        private readonly string $mockedClientBuilder,
        private readonly array $mockedArgs = []
    ) {
        $this->node = new Node\Expr\New_(
            class: new Node\Name\FullyQualified($this->mockedClientBuilder),
            args: $this->mockedArgs,
        );
    }

    public function withHttpClient(Mock\HttpClientBuilder $httpClient): self
    {
        $this->node = new Node\Expr\MethodCall(
            var: $this->node,
            name: new Node\Identifier('setHttpClient'),
            args: [
                new Node\Arg(
                    $httpClient->getNode(),
                ),
            ],
        );

        return $this;
    }

    public function withRequestFactory(Mock\RequestFactoryBuilder $requestFactory): self
    {
        $this->node = new Node\Expr\MethodCall(
            var: $this->node,
            name: new Node\Identifier('setRequestFactory'),
            args: [
                new Node\Arg(
                    $requestFactory->getNode(),
                ),
            ],
        );

        return $this;
    }

    public function withStreamFactory(Mock\StreamFactoryBuilder $streamFactory): self
    {
        $this->node = new Node\Expr\MethodCall(
            var: $this->node,
            name: new Node\Identifier('setStreamFactory'),
            args: [
                new Node\Arg(
                    $streamFactory->getNode(),
                ),
            ],
        );

        return $this;
    }

    public function withFileSystem(Mock\FileSystemBuilder $fileSystem): self
    {
        $this->node = new Node\Expr\MethodCall(
            var: $this->node,
            name: new Node\Identifier('setFileSystem'),
            args: [
                new Node\Arg(
                    $fileSystem->getNode(),
                ),
            ],
        );

        return $this;
    }

    public function withAuthenticatedByPassword(): self
    {
        $faker = Factory::create();

        $this->node = new Node\Expr\MethodCall(
            var: $this->node,
            name: new Node\Identifier('buildAuthenticatedByPassword'),
            args: [
                new Node\Arg(
                    new Node\Scalar\String_($faker->regexify('\d{1,2}_[0-9a-f]{48}')),
                ),
                new Node\Arg(
                    new Node\Scalar\String_($faker->regexify('[0-9a-z]{64}')),
                ),
                new Node\Arg(
                    new Node\Scalar\String_($faker->userName()),
                ),
                new Node\Arg(
                    new Node\Scalar\String_($faker->password()),
                ),
            ],
        );

        return $this;
    }

    public function withAuthenticatedByToken(): self
    {
        $faker = Factory::create();

        $this->node = new Node\Expr\MethodCall(
            var: $this->node,
            name: new Node\Identifier('buildAuthenticatedByToken'),
            args: [
                new Node\Arg(
                    new Node\Scalar\String_($faker->regexify('\d{1,2}_[0-9a-f]{48}')),
                ),
                new Node\Arg(
                    new Node\Scalar\String_($faker->regexify('[0-9a-z]{64}')),
                ),
                new Node\Arg(
                    new Node\Scalar\String_($faker->regexify('[0-9a-z/-]{48}')),
                ),
                new Node\Arg(
                    new Node\Scalar\String_($faker->regexify('[0-9a-z/-]{128}')),
                ),
            ],
        );

        return $this;
    }

    public function getNode(): Node\Expr
    {
        return $this->node;
    }
}
