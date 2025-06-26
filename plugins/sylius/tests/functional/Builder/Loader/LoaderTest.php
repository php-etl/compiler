<?php

declare(strict_types=1);

namespace functional\Kiboko\Plugin\Sylius\Builder\Loader;

use Diglin\Sylius\ApiClient\SyliusLegacyClientFactory;
use Kiboko\Component\PHPUnitExtension\Assert\LoaderBuilderAssertTrait;
use Kiboko\Component\PHPUnitExtension\BuilderTestCase;
use Kiboko\Component\PHPUnitExtension\Mock;
use Kiboko\Plugin\Sylius\Builder\Loader;
use Kiboko\Plugin\Sylius\Capacity;

final class LoaderTest extends BuilderTestCase
{
    use LoaderBuilderAssertTrait;

    public function testUpsertProduct(): void
    {
        $httpClient = new Mock\HttpClientBuilder(new Mock\ResponseFactoryBuilder());

        $httpClient
            ->expectResponse(
                new Mock\RequestMatcher\RequestMatcherBuilder('/api/oauth/v2/token', methods: ['POST']),
                new Mock\ResponseBuilder(__DIR__.'/../token.php')
            )
            ->expectResponse(
                new Mock\RequestMatcher\RequestMatcherBuilder('/api/oauth/v2/products/[^/]+', methods: ['PATCH']),
                new Mock\ResponseBuilder(__DIR__.'/post-product.php')
            )
        ;

        $client = new Mock\ApiClientMockBuilder(SyliusLegacyClientFactory::class);

        $client
            ->withHttpClient($httpClient)
            ->withRequestFactory(new Mock\RequestFactoryBuilder())
            ->withStreamFactory(new Mock\StreamFactoryBuilder())
            ->withAuthenticatedByPassword()
        ;

        $capacity = (new Capacity\Upsert())->getBuilder([
            'type' => 'products',
            'method' => 'all',
            'code' => 'line[code]',
        ]);

        $builder = new Loader($capacity);
        $builder->withClient($client->getNode());

        $this->assertBuildsLoaderLoadsExactly(
            [
                [
                    'code' => '0987uiop',
                ],
            ],
            [
                [
                    'code' => '0987uiop',
                ],
            ],
            $builder,
        );
    }

    public function testCreateProduct(): void
    {
        $httpClient = new Mock\HttpClientBuilder(new Mock\ResponseFactoryBuilder());

        $httpClient
            ->expectResponse(
                new Mock\RequestMatcher\RequestMatcherBuilder('/api/oauth/v2/token', methods: ['POST']),
                new Mock\ResponseBuilder(__DIR__.'/../token.php')
            )
            ->expectResponse(
                new Mock\RequestMatcher\RequestMatcherBuilder('/api/oauth/v2/products/[^/]+', methods: ['PATCH']),
                new Mock\ResponseBuilder(__DIR__.'/post-product.php')
            )
        ;

        $client = new Mock\ApiClientMockBuilder(SyliusLegacyClientFactory::class);

        $client
            ->withHttpClient($httpClient)
            ->withRequestFactory(new Mock\RequestFactoryBuilder())
            ->withStreamFactory(new Mock\StreamFactoryBuilder())
            ->withAuthenticatedByPassword()
        ;

        $capacity = (new Capacity\Create())->getBuilder([
            'type' => 'products',
            'method' => 'all',
            'code' => 'line[code]',
        ]);

        $builder = new Loader($capacity);
        $builder->withClient($client->getNode());

        $this->assertBuildsLoaderLoadsExactly(
            [
                [
                    'code' => '0987uiop',
                ],
            ],
            [
                [
                    'code' => '0987uiop',
                ],
            ],
            $builder,
        );
    }
}
