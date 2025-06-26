<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Prestashop;

use Kiboko\Contract\Configurator;
use Symfony\Component\Config\Definition\Exception as Symfony;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

#[Configurator\Pipeline(
    name: 'prestashop',
    dependencies: [
        'php-etl/prestashop-api-client:0.1.*',
    ],
    steps: [
        new Configurator\Pipeline\StepExtractor(),
        new Configurator\Pipeline\StepLoader(),
    ],
)]
final readonly class Service implements Configurator\PipelinePluginInterface
{
    private Processor $processor;
    private Configurator\PluginConfigurationInterface $configuration;

    public function __construct(private ExpressionLanguage $interpreter = new ExpressionLanguage())
    {
        $this->processor = new Processor();
        $this->configuration = new Configuration();
    }

    public function interpreter(): ExpressionLanguage
    {
        return $this->interpreter;
    }

    public function configuration(): Configurator\PluginConfigurationInterface
    {
        return $this->configuration;
    }

    public function normalize(array $config): array
    {
        try {
            return $this->processor->processConfiguration($this->configuration, $config);
        } catch (Symfony\InvalidTypeException|Symfony\InvalidConfigurationException $exception) {
            throw new Configurator\InvalidConfigurationException($exception->getMessage(), 0, $exception);
        }
    }

    public function validate(array $config): bool
    {
        if ($this->processor->processConfiguration($this->configuration, $config)) {
            return true;
        }

        return false;
    }

    public function compile(array $config): Configurator\RepositoryInterface
    {
        $clientFactory = new Factory\Client($this->interpreter);

        try {
            $client = $clientFactory->compile($config['client']);

            if (\array_key_exists('extractor', $config)) {
                $extractor = (new Factory\Extractor($this->interpreter))->compile($config['extractor']);

                $extractorBuilder = $extractor->getBuilder();
                $extractorBuilder->withClient($client->getBuilder()->getNode());

                $extractor->merge($client);

                return $extractor;
            }
            if (\array_key_exists('loader', $config)) {
                $loader = (new Factory\Loader())->compile($config['loader']);

                $loaderBuilder = $loader->getBuilder();
                $loaderBuilder->withClient($client->getBuilder()->getNode());

                $loader->merge($client);

                return $loader;
            }
        } catch (Symfony\InvalidTypeException|Symfony\InvalidConfigurationException $exception) {
            throw new Configurator\InvalidConfigurationException($exception->getMessage(), previous: $exception);
        }

        throw new Configurator\InvalidConfigurationException('Could not determine if the factory should build an extractor or a loader.');
    }
}
