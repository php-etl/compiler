<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Prestashop\Factory;

use Kiboko\Contract\Configurator;
use Kiboko\Contract\Configurator\FactoryInterface;
use Kiboko\Plugin\Prestashop;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception as Symfony;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

final readonly class Extractor implements FactoryInterface
{
    private Processor $processor;
    private ConfigurationInterface $configuration;
    /** @var iterable<Prestashop\Capacity\CapacityInterface> */
    private iterable $capacities;

    public function __construct(private ExpressionLanguage $interpreter)
    {
        $this->processor = new Processor();
        $this->configuration = new Prestashop\Configuration\Extractor();
        $this->capacities = [
            new Prestashop\Capacity\All($this->interpreter),
            new Prestashop\Capacity\Get($this->interpreter),
        ];
    }

    public function configuration(): ConfigurationInterface
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
        try {
            $this->processor->processConfiguration($this->configuration, $config);

            return true;
        } catch (Symfony\InvalidTypeException|Symfony\InvalidConfigurationException) {
            return false;
        }
    }

    private function findCapacity(array $config): Prestashop\Capacity\CapacityInterface
    {
        foreach ($this->capacities as $capacity) {
            if ($capacity->applies($config)) {
                return $capacity;
            }
        }

        throw new \Exception(message: 'No capacity was able to handle the configuration.');
    }

    public function compile(array $config): Repository\Extractor
    {
        $builder = new Prestashop\Builder\Extractor(
            $this->findCapacity($config)->getBuilder($config)
        );

        return new Repository\Extractor($builder);
    }
}
