<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Sylius\Factory;

use Kiboko\Contract\Configurator;
use Kiboko\Plugin\Sylius;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception as Symfony;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

final readonly class Extractor implements Configurator\FactoryInterface
{
    private Processor $processor;
    private ConfigurationInterface $configuration;
    /** @var iterable<Sylius\Capacity\CapacityInterface> */
    private iterable $capacities;

    public function __construct(private ExpressionLanguage $interpreter)
    {
        $this->processor = new Processor();
        $this->configuration = new Sylius\Configuration\Extractor();
        $this->capacities = [
            new Sylius\Capacity\All($this->interpreter),
            new Sylius\Capacity\ListPerPage($this->interpreter),
        ];
    }

    public function configuration(): ConfigurationInterface
    {
        return $this->configuration;
    }

    /**
     * @throws Configurator\ConfigurationExceptionInterface
     */
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
        try {
            $this->normalize($config);

            return true;
        } catch (Configurator\InvalidConfigurationException) {
            return false;
        }
    }

    private function findCapacity(array $config): Sylius\Capacity\CapacityInterface
    {
        foreach ($this->capacities as $capacity) {
            if ($capacity->applies($config)) {
                return $capacity;
            }
        }

        throw new NoApplicableCapacityException(message: 'No capacity was able to handle the configuration.');
    }

    public function compile(array $config): Repository\Extractor
    {
        try {
            $builder = new Sylius\Builder\Extractor(
                $this->findCapacity($config)->getBuilder($config)
            );
        } catch (NoApplicableCapacityException $exception) {
            throw new Configurator\InvalidConfigurationException(message: 'Your Sylius API configuration is using some unsupported capacity, check your "type" and "method" properties to a suitable set.', previous: $exception);
        }

        return new Repository\Extractor($builder);
    }
}
