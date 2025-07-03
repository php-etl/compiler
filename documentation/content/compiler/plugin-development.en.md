---
title: "Plugin Development Guide"
date: 2024-01-01T00:00:00Z
draft: false
weight: 4
description: "Comprehensive guide for creating custom plugins for the Gyroscops Compiler"
---

# Plugin Development Guide

This guide provides comprehensive instructions for developing custom plugins for the Gyroscops Compiler. Plugins extend the compiler's functionality by adding new data sources, destinations, transformations, and features.

## Plugin Types

The Gyroscops Compiler supports several types of plugins:

### 1. Pipeline Plugins
- **Extractors**: Read data from sources (databases, APIs, files)
- **Loaders**: Write data to destinations (databases, APIs, files)
- **Transformers**: Transform data between extraction and loading

### 2. Feature Plugins
- **Cross-cutting concerns**: Logging, monitoring, state management
- **Utilities**: Caching, validation, error handling

### 3. Action Plugins
- **Standalone operations**: Cleanup tasks, maintenance operations
- **Scheduled jobs**: Cron-like functionality

### 4. Runtime Plugins
- **Execution environments**: Docker, Kubernetes, cloud platforms
- **Resource management**: Scaling, monitoring, deployment

## Plugin Architecture

### Core Interfaces

Every plugin must implement the appropriate interface:

```php
// Pipeline plugins
interface PipelinePluginInterface
{
    public function interpreter(): ExpressionLanguage;
    public function configuration(): PluginConfigurationInterface;
    public function normalize(array $config): array;
    public function validate(array $config): bool;
    public function compile(array $config): RepositoryInterface;
}

// Feature plugins
interface FeatureInterface
{
    public function configuration(): FeatureConfigurationInterface;
    public function compile(array $config): FeatureRepositoryInterface;
}

// Action plugins
interface ActionInterface
{
    public function configuration(): ActionConfigurationInterface;
    public function compile(array $config): ActionRepositoryInterface;
}
```

### Plugin Attributes

Plugins are registered using PHP attributes:

```php
#[Configurator\Pipeline(
    name: 'my-plugin',
    dependencies: [
        'vendor/package',
        'another/dependency',
    ],
    steps: [
        new Configurator\Pipeline\StepExtractor(),
        new Configurator\Pipeline\StepLoader(),
    ],
)]
final readonly class MyPlugin implements PipelinePluginInterface
{
    // Implementation
}
```

## Creating a Pipeline Plugin

### Step 1: Project Setup

Create a new PHP project with the following structure:

```
my-plugin/
├── composer.json
├── src/
│   ├── Service.php
│   ├── Configuration.php
│   ├── Configuration/
│   │   ├── Extractor.php
│   │   └── Loader.php
│   ├── Factory/
│   │   ├── Extractor.php
│   │   └── Loader.php
│   └── Builder/
│       ├── Extractor.php
│       └── Loader.php
├── tests/
└── README.md
```

### Step 2: Composer Configuration

```json
{
    "name": "my-vendor/my-plugin",
    "description": "Custom plugin for Gyroscops Compiler",
    "type": "gyroscops-plugin",
    "license": "MIT",
    "require": {
        "php": "^8.2",
        "php-etl/configurator-contracts": "^0.8",
        "php-etl/packaging-contracts": "^0.3",
        "symfony/config": "^6.0",
        "symfony/expression-language": "^6.0",
        "nikic/php-parser": "^4.13"
    },
    "autoload": {
        "psr-4": {
            "MyVendor\\MyPlugin\\": "src/"
        }
    },
    "extra": {
        "gyroscops": {
            "plugins": ["MyVendor\\MyPlugin\\Service"]
        }
    }
}
```

### Step 3: Main Service Class

```php
<?php

declare(strict_types=1);

namespace MyVendor\MyPlugin;

use Kiboko\Contract\Configurator;
use Symfony\Component\Config\Definition\Exception as Symfony;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

#[Configurator\Pipeline(
    name: 'my-plugin',
    dependencies: [
        'my-vendor/my-client',
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

    public function __construct(
        private ExpressionLanguage $interpreter = new ExpressionLanguage()
    ) {
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
            throw new Configurator\InvalidConfigurationException(
                $exception->getMessage(), 
                0, 
                $exception
            );
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

    public function compile(array $config): Configurator\RepositoryInterface
    {
        $interpreter = clone $this->interpreter;

        // Register expression language providers if needed
        if (array_key_exists('expression_language', $config)) {
            foreach ($config['expression_language'] as $provider) {
                $interpreter->registerProvider(new $provider());
            }
        }

        // Compile based on configuration type
        if (array_key_exists('extractor', $config)) {
            return $this->compileExtractor($config, $interpreter);
        }

        if (array_key_exists('loader', $config)) {
            return $this->compileLoader($config, $interpreter);
        }

        throw new Configurator\InvalidConfigurationException(
            'Could not determine if the factory should build an extractor or a loader.'
        );
    }

    private function compileExtractor(array $config, ExpressionLanguage $interpreter): Factory\Repository\Extractor
    {
        $extractorFactory = new Factory\Extractor($interpreter);
        return $extractorFactory->compile($config['extractor']);
    }

    private function compileLoader(array $config, ExpressionLanguage $interpreter): Factory\Repository\Loader
    {
        $loaderFactory = new Factory\Loader($interpreter);
        return $loaderFactory->compile($config['loader']);
    }
}
```

### Step 4: Configuration Schema

```php
<?php

declare(strict_types=1);

namespace MyVendor\MyPlugin;

use Kiboko\Contract\Configurator\PluginConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

final class Configuration implements PluginConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $extractor = new Configuration\Extractor();
        $loader = new Configuration\Loader();

        $builder = new TreeBuilder('my-plugin');

        $builder->getRootNode()
            ->validate()
                ->ifTrue(fn (array $value) => 
                    array_key_exists('extractor', $value) && 
                    array_key_exists('loader', $value)
                )
                ->thenInvalid('Your configuration should either contain the "extractor" or the "loader" key, not both.')
            ->end()
            ->children()
                ->arrayNode('expression_language')
                    ->scalarPrototype()
                ->end()
            ->end()
            ->append($extractor->getConfigTreeBuilder()->getRootNode())
            ->append($loader->getConfigTreeBuilder()->getRootNode())
        ;

        return $builder;
    }
}
```

### Step 5: Extractor Configuration

```php
<?php

declare(strict_types=1);

namespace MyVendor\MyPlugin\Configuration;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

use function Kiboko\Component\SatelliteToolbox\Configuration\asExpression;
use function Kiboko\Component\SatelliteToolbox\Configuration\isExpression;

final class Extractor implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder('extractor');

        $builder->getRootNode()
            ->children()
                ->scalarNode('endpoint')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->validate()
                        ->ifTrue(isExpression())
                        ->then(asExpression())
                    ->end()
                ->end()
                ->scalarNode('method')
                    ->defaultValue('GET')
                    ->validate()
                        ->ifTrue(isExpression())
                        ->then(asExpression())
                    ->end()
                ->end()
                ->arrayNode('headers')
                    ->arrayPrototype()
                        ->validate()
                            ->ifTrue(isExpression())
                            ->then(asExpression())
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('parameters')
                    ->arrayPrototype()
                        ->validate()
                            ->ifTrue(isExpression())
                            ->then(asExpression())
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $builder;
    }
}
```

### Step 6: Factory Classes

```php
<?php

declare(strict_types=1);

namespace MyVendor\MyPlugin\Factory;

use Kiboko\Contract\Configurator;
use MyVendor\MyPlugin\Builder;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

final readonly class Extractor implements Configurator\FactoryInterface
{
    public function __construct(
        private ExpressionLanguage $interpreter
    ) {}

    public function configuration(): Configurator\ConfigurationInterface
    {
        return new Configuration\Extractor();
    }

    public function normalize(array $config): array
    {
        // Normalization logic
        return $config;
    }

    public function validate(array $config): bool
    {
        // Validation logic
        return true;
    }

    public function compile(array $config): Repository\Extractor
    {
        $builder = new Builder\Extractor(
            endpoint: $config['endpoint'],
            method: $config['method'] ?? 'GET',
            headers: $config['headers'] ?? [],
            parameters: $config['parameters'] ?? []
        );

        return new Repository\Extractor($builder);
    }
}
```

### Step 7: Builder Classes

```php
<?php

declare(strict_types=1);

namespace MyVendor\MyPlugin\Builder;

use Kiboko\Contract\Configurator\BuilderInterface;
use PhpParser\Builder;
use PhpParser\Node;

final class Extractor implements BuilderInterface
{
    private ?Node\Expr $logger = null;
    private ?Node\Expr $state = null;

    public function __construct(
        private string|Node\Expr $endpoint,
        private string|Node\Expr $method = 'GET',
        private array $headers = [],
        private array $parameters = []
    ) {}

    public function withLogger(Node\Expr $logger): self
    {
        $this->logger = $logger;
        return $this;
    }

    public function withState(Node\Expr $state): self
    {
        $this->state = $state;
        return $this;
    }

    public function getNode(): Node
    {
        return (new Builder\Class_('Extractor'))
            ->implement('Iterator')
            ->addStmt(
                (new Builder\Method('__construct'))
                    ->makePublic()
                    ->addParam(
                        (new Builder\Param('endpoint'))
                            ->setType('string')
                    )
                    // Add other constructor parameters
                    ->addStmt(
                        new Node\Stmt\Expression(
                            new Node\Expr\Assign(
                                new Node\Expr\PropertyFetch(
                                    new Node\Expr\Variable('this'),
                                    'endpoint'
                                ),
                                new Node\Expr\Variable('endpoint')
                            )
                        )
                    )
            )
            ->addStmt(
                (new Builder\Method('current'))
                    ->makePublic()
                    ->setReturnType('mixed')
                    ->addStmt(
                        // Implementation for current()
                        new Node\Stmt\Return_(
                            new Node\Expr\Array_()
                        )
                    )
            )
            ->addStmt(
                (new Builder\Method('next'))
                    ->makePublic()
                    ->setReturnType('void')
                    ->addStmt(
                        // Implementation for next()
                        new Node\Stmt\Nop()
                    )
            )
            ->addStmt(
                (new Builder\Method('key'))
                    ->makePublic()
                    ->setReturnType('mixed')
                    ->addStmt(
                        // Implementation for key()
                        new Node\Stmt\Return_(
                            new Node\Scalar\LNumber(0)
                        )
                    )
            )
            ->addStmt(
                (new Builder\Method('valid'))
                    ->makePublic()
                    ->setReturnType('bool')
                    ->addStmt(
                        // Implementation for valid()
                        new Node\Stmt\Return_(
                            new Node\Expr\ConstFetch(
                                new Node\Name('false')
                            )
                        )
                    )
            )
            ->addStmt(
                (new Builder\Method('rewind'))
                    ->makePublic()
                    ->setReturnType('void')
                    ->addStmt(
                        // Implementation for rewind()
                        new Node\Stmt\Nop()
                    )
            )
            ->getNode()
        ;
    }
}
```

### Step 8: Repository Classes

```php
<?php

declare(strict_types=1);

namespace MyVendor\MyPlugin\Factory\Repository;

use Kiboko\Contract\Configurator;
use MyVendor\MyPlugin\Builder;
use PhpParser\Node;

final class Extractor implements Configurator\Factory\Repository\Extractor
{
    use Configurator\Repository\RepositoryTrait;

    public function __construct(
        private Builder\Extractor $builder,
    ) {
        $this->files = [];
        $this->packages = [];
    }

    public function getBuilder(): Builder\Extractor
    {
        return $this->builder;
    }

    public function merge(Configurator\RepositoryInterface $friend): self
    {
        array_push($this->files, ...$friend->getFiles());
        array_push($this->packages, ...$friend->getPackages());

        return $this;
    }

    public function addPackage(string ...$packages): self
    {
        array_push($this->packages, ...$packages);

        return $this;
    }

    public function getNode(): Node
    {
        return $this->builder->getNode();
    }

    public function getExtractor(): Builder\Extractor
    {
        return $this->builder;
    }

    public function withExtractor(Configurator\BuilderInterface $extractor): self
    {
        $this->builder = $extractor;

        return $this;
    }
}
```

## Testing Your Plugin

### Unit Tests

```php
<?php

declare(strict_types=1);

namespace MyVendor\MyPlugin\Tests\Unit;

use MyVendor\MyPlugin\Service;
use PHPUnit\Framework\TestCase;

final class ServiceTest extends TestCase
{
    public function testValidateValidConfiguration(): void
    {
        $service = new Service();
        
        $config = [
            'extractor' => [
                'endpoint' => 'https://api.example.com/data',
                'method' => 'GET',
            ]
        ];

        $this->assertTrue($service->validate($config));
    }

    public function testValidateInvalidConfiguration(): void
    {
        $service = new Service();
        
        $config = [
            'extractor' => [
                // Missing required endpoint
                'method' => 'GET',
            ]
        ];

        $this->assertFalse($service->validate($config));
    }

    public function testNormalizeConfiguration(): void
    {
        $service = new Service();
        
        $config = [
            'extractor' => [
                'endpoint' => 'https://api.example.com/data',
            ]
        ];

        $normalized = $service->normalize($config);
        
        $this->assertEquals('GET', $normalized['extractor']['method']);
    }

    public function testCompileExtractor(): void
    {
        $service = new Service();
        
        $config = [
            'extractor' => [
                'endpoint' => 'https://api.example.com/data',
                'method' => 'GET',
            ]
        ];

        $repository = $service->compile($config);
        
        $this->assertInstanceOf(Factory\Repository\Extractor::class, $repository);
    }
}
```

### Integration Tests

```php
<?php

declare(strict_types=1);

namespace MyVendor\MyPlugin\Tests\Integration;

use MyVendor\MyPlugin\Service;
use PhpParser\PrettyPrinter\Standard;
use PHPUnit\Framework\TestCase;

final class CompilationTest extends TestCase
{
    public function testCompileAndGenerateCode(): void
    {
        $service = new Service();
        
        $config = [
            'extractor' => [
                'endpoint' => 'https://api.example.com/data',
                'method' => 'GET',
                'headers' => [
                    'Authorization' => 'Bearer token123',
                ],
            ]
        ];

        $repository = $service->compile($config);
        $node = $repository->getNode();
        
        $printer = new Standard();
        $code = $printer->prettyPrint([$node]);
        
        $this->assertStringContainsString('class Extractor', $code);
        $this->assertStringContainsString('implements Iterator', $code);
    }
}
```

## Best Practices

### 1. Configuration Design

- Use clear, descriptive configuration keys
- Provide sensible defaults
- Support expression language for dynamic values
- Validate configuration thoroughly

```php
// Good configuration design
$builder->getRootNode()
    ->children()
        ->scalarNode('endpoint')
            ->isRequired()
            ->cannotBeEmpty()
            ->info('The API endpoint URL')
        ->end()
        ->scalarNode('timeout')
            ->defaultValue(30)
            ->info('Request timeout in seconds')
        ->end()
        ->booleanNode('verify_ssl')
            ->defaultTrue()
            ->info('Whether to verify SSL certificates')
        ->end()
    ->end()
;
```

### 2. Error Handling

- Provide meaningful error messages
- Use appropriate exception types
- Include context in error messages

```php
try {
    $response = $this->client->request($method, $endpoint);
} catch (RequestException $e) {
    throw new Configurator\InvalidConfigurationException(
        sprintf(
            'Failed to connect to endpoint "%s": %s',
            $endpoint,
            $e->getMessage()
        ),
        0,
        $e
    );
}
```

### 3. Performance Optimization

- Use generators for large datasets
- Implement lazy loading
- Cache expensive operations
- Minimize memory usage

```php
public function extract(): \Generator
{
    $page = 1;
    
    do {
        $response = $this->fetchPage($page);
        $data = $response['data'];
        
        foreach ($data as $item) {
            yield $item;
        }
        
        $page++;
    } while (!empty($data));
}
```

### 4. Documentation

- Document all configuration options
- Provide usage examples
- Include troubleshooting guides
- Document expression language functions

### 5. Expression Language Integration

```php
// Register custom expression functions
public function interpreter(): ExpressionLanguage
{
    $interpreter = new ExpressionLanguage();
    $interpreter->registerProvider(new MyCustomExpressionProvider());
    return $interpreter;
}

// Custom expression provider
class MyCustomExpressionProvider implements ExpressionFunctionProviderInterface
{
    public function getFunctions(): array
    {
        return [
            new ExpressionFunction(
                'format_date',
                function ($date, $format) {
                    return sprintf('(new DateTime(%s))->format(%s)', $date, $format);
                },
                function ($arguments, $date, $format) {
                    return (new \DateTime($date))->format($format);
                }
            ),
        ];
    }
}
```

## Plugin Registration

### Automatic Discovery

Plugins are automatically discovered through Composer's `extra.gyroscops.plugins` configuration:

```json
{
    "extra": {
        "gyroscops": {
            "plugins": [
                "MyVendor\\MyPlugin\\Service",
                "MyVendor\\AnotherPlugin\\Service"
            ]
        }
    }
}
```

### Manual Registration

```php
use Kiboko\Component\Satellite\Service as Compiler;
use MyVendor\MyPlugin\Service as MyPlugin;

$compiler = new Compiler();
$compiler->addPipelinePlugin(
    new \Kiboko\Contract\Configurator\Pipeline(name: 'my-plugin'),
    new MyPlugin()
);
```

## Publishing Your Plugin

### 1. Package on Packagist

- Create a repository on GitHub/GitLab
- Tag releases following semantic versioning
- Submit to Packagist.org

### 2. Documentation

- Create comprehensive README
- Include configuration examples
- Document all features and limitations

### 3. Testing

- Ensure 100% test coverage
- Test with different PHP versions
- Test integration with the compiler

### 4. Community

- Follow coding standards (PSR-12)
- Use static analysis tools (PHPStan)
- Provide support channels

This guide provides the foundation for creating robust, well-tested plugins that extend the Gyroscops Compiler's capabilities. Follow these patterns and best practices to ensure your plugins integrate seamlessly with the ecosystem.