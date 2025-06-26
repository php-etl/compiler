<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Sylius;

use Kiboko\Contract\Configurator;
use Kiboko\Contract\Configurator\ConfigurationExceptionInterface;
use Kiboko\Contract\Configurator\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Exception as Symfony;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

#[Configurator\Pipeline(
    name: 'sylius',
    dependencies: [
        'php-etl/sylius-api-php-client:^2.0',
        'laminas/laminas-diactoros',
        'php-http/guzzle7-adapter',
    ],
    steps: [
        new Configurator\Pipeline\StepExtractor(),
        new Configurator\Pipeline\StepLoader(),
    ],
)] final readonly class Service implements Configurator\PipelinePluginInterface
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

    /**
     * @throws ConfigurationExceptionInterface
     */
    public function normalize(array $config): array
    {
        try {
            return $this->processor->processConfiguration($this->configuration, $config);
        } catch (Symfony\InvalidTypeException|Symfony\InvalidConfigurationException $exception) {
            throw new InvalidConfigurationException($exception->getMessage(), 0, $exception);
        }
    }

    public function validate(array $config): bool
    {
        try {
            $this->processor->processConfiguration($this->configuration, $config);

            return true;
        } catch (Symfony\InvalidTypeException|Symfony\InvalidConfigurationException) {
            return false;
        }
    }

    /**
     * @throws ConfigurationExceptionInterface
     */
    public function compile(array $config): Factory\Repository\Extractor|Factory\Repository\Loader
    {
        if (\array_key_exists('expression_language', $config)
            && \is_array($config['expression_language'])
            && \count($config['expression_language'])
        ) {
            foreach ($config['expression_language'] as $provider) {
                $this->interpreter->registerProvider(new $provider());
            }
        }

        $clientFactory = new Factory\Client($this->interpreter);

        try {
            if (\array_key_exists('extractor', $config)) {
                $extractorFactory = new Factory\Extractor($this->interpreter);

                $extractor = $extractorFactory->compile($config['extractor']);
                $extractorBuilder = $extractor->getBuilder();

                $client = $clientFactory->compile($config['client']);

                $extractorBuilder->withClient($client->getBuilder()->getNode());

                $extractor->merge($client);

                return $extractor;
            }
            if (\array_key_exists('loader', $config)) {
                $loaderFactory = new Factory\Loader();

                $loader = $loaderFactory->compile($config['loader']);
                $loaderBuilder = $loader->getBuilder();

                $client = $clientFactory->compile($config['client']);

                $loaderBuilder->withClient($client->getBuilder()->getNode());

                $loader->merge($client);

                return $loader;
            }
            throw new InvalidConfigurationException('Could not determine if the factory should build an extractor or a loader.');
        } catch (MissingAuthenticationMethodException $exception) {
            throw new InvalidConfigurationException('Your Sylius API configuration is missing an authentication method, you should either define "username" or "token" options.', 0, $exception);
        } catch (Symfony\InvalidTypeException|Symfony\InvalidConfigurationException $exception) {
            throw new InvalidConfigurationException($exception->getMessage(), 0, $exception);
        }
    }
}
