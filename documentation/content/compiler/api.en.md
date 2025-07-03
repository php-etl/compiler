---
title: "API Reference"
date: 2024-01-01T00:00:00Z
draft: false
weight: 3
description: "Detailed API documentation for developers working with the Gyroscops Compiler"
---

# API Reference

This document provides detailed API documentation for developers working with the Gyroscops Compiler. It covers the main classes, interfaces, and methods available for programmatic use.

## Core Interfaces

### FactoryInterface

The main interface for the compiler service.

```php
namespace Kiboko\Component\Satellite;

interface FactoryInterface
{
    public function configuration(): ConfigurationInterface;
    public function normalize(array $config): array;
    public function validate(array $config): bool;
    public function compile(array $config): RepositoryInterface;
}
```

### Service Class

The main compiler service that implements `FactoryInterface`.

```php
namespace Kiboko\Component\Satellite;

use Kiboko\Contract\Configurator;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class Service implements FactoryInterface
{
    public function __construct(ExpressionLanguage $expressionLanguage);
    
    // Component Registration
    public function addAdapter(
        Configurator\Adapter $attribute, 
        Configurator\Adapter\FactoryInterface $adapter
    ): self;
    
    public function addRuntime(
        Configurator\Runtime $attribute, 
        Satellite\Runtime\FactoryInterface $runtime
    ): self;
    
    public function addFeature(
        Configurator\Feature $attribute, 
        Configurator\FactoryInterface $feature
    ): self;
    
    public function addPipelinePlugin(
        Configurator\Pipeline $attribute, 
        Configurator\PipelinePluginInterface $plugin
    ): self;
    
    public function addAction(
        Configurator\Action $attribute, 
        Configurator\ActionInterface $action
    ): self;
    
    // Bulk Registration
    public function registerAdapters(
        Configurator\Adapter\FactoryInterface $adapters
    ): self;
    
    public function registerRuntimes(
        Satellite\Runtime\FactoryInterface $runtimes
    ): self;
    
    public function registerPlugins(
        Configurator\PipelineFeatureInterface|Configurator\PipelinePluginInterface $plugins
    ): self;
    
    public function registerActions(
        Configurator\ActionInterface $actions
    ): self;
    
    // Configuration and Compilation
    public function configuration(): ConfigurationInterface;
    public function normalize(array $config): array;
    public function validate(array $config): bool;
    public function compile(array $config): Configurator\RepositoryInterface;
    
    // Specific Compilation Methods
    public function compileWorkflow(array $config): Satellite\Builder\Repository\Workflow;
    public function compilePipelineJob(array $config): Satellite\Builder\Repository\Pipeline;
    public function compileActionJob(array $config): Satellite\Builder\Repository\Action;
    public function compilePipeline(array $config): Satellite\Builder\Repository\Pipeline;
    public function compileApi(array $config): Satellite\Builder\Repository\API;
    public function compileHook(array $config): Satellite\Builder\Repository\Hook;
}
```

## Configuration System

### ConfigurationInterface

```php
namespace Symfony\Component\Config\Definition;

interface ConfigurationInterface
{
    public function getConfigTreeBuilder(): Builder\TreeBuilder;
}
```

### Configuration Class

```php
namespace Kiboko\Component\Satellite;

use Kiboko\Contract\Configurator;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function addAdapter(
        string $name, 
        Configurator\AdapterConfigurationInterface $adapter
    ): self;
    
    public function addRuntime(
        string $name, 
        Configurator\RuntimeConfigurationInterface $runtime
    ): self;
    
    public function addPlugin(
        string $name, 
        Configurator\PluginConfigurationInterface $plugin
    ): self;
    
    public function addFeature(
        string $name, 
        Configurator\FeatureConfigurationInterface $feature
    ): self;
    
    public function addAction(
        string $name, 
        Configurator\ActionConfigurationInterface $action
    ): self;
    
    public function getConfigTreeBuilder(): Builder\TreeBuilder;
}
```

## Plugin Development Interfaces

### PipelinePluginInterface

Interface for creating pipeline plugins (extractors, loaders, transformers).

```php
namespace Kiboko\Contract\Configurator;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

interface PipelinePluginInterface
{
    /**
     * Get the expression language interpreter for this plugin
     */
    public function interpreter(): ExpressionLanguage;
    
    /**
     * Get the configuration schema for this plugin
     */
    public function configuration(): PluginConfigurationInterface;
    
    /**
     * Normalize the plugin configuration
     * 
     * @param array $config Raw configuration array
     * @return array Normalized configuration
     * @throws ConfigurationExceptionInterface
     */
    public function normalize(array $config): array;
    
    /**
     * Validate the plugin configuration
     * 
     * @param array $config Configuration to validate
     * @return bool True if valid, false otherwise
     */
    public function validate(array $config): bool;
    
    /**
     * Compile the plugin configuration into executable code
     * 
     * @param array $config Validated configuration
     * @return RepositoryInterface Compiled code repository
     * @throws ConfigurationExceptionInterface
     */
    public function compile(array $config): RepositoryInterface;
}
```

### PluginConfigurationInterface

```php
namespace Kiboko\Contract\Configurator;

use Symfony\Component\Config\Definition\ConfigurationInterface;

interface PluginConfigurationInterface extends ConfigurationInterface
{
    // Inherits getConfigTreeBuilder() from ConfigurationInterface
}
```

### RepositoryInterface

Interface for compiled code repositories.

```php
namespace Kiboko\Contract\Configurator;

use PhpParser\Node;

interface RepositoryInterface
{
    /**
     * Get the compiled AST node
     */
    public function getNode(): Node;
    
    /**
     * Get the builder for this repository
     */
    public function getBuilder(): BuilderInterface;
    
    /**
     * Merge another repository into this one
     */
    public function merge(RepositoryInterface $repository): self;
    
    /**
     * Get package dependencies
     */
    public function getPackages(): iterable;
    
    /**
     * Get namespace dependencies
     */
    public function getNamespaces(): iterable;
}
```

## Builder System

### BuilderInterface

```php
namespace Kiboko\Contract\Configurator;

use PhpParser\Node;

interface BuilderInterface
{
    /**
     * Get the built AST node
     */
    public function getNode(): Node;
    
    /**
     * Add a dependency to the builder
     */
    public function withDependency(string $package): self;
    
    /**
     * Add a namespace import
     */
    public function withNamespace(string $namespace): self;
}
```

### Repository Types

#### Extractor Repository

```php
namespace Kiboko\Contract\Configurator\Factory\Repository;

interface Extractor extends RepositoryInterface
{
    public function getExtractor(): BuilderInterface;
    public function withExtractor(BuilderInterface $extractor): self;
}
```

#### Loader Repository

```php
namespace Kiboko\Contract\Configurator\Factory\Repository;

interface Loader extends RepositoryInterface
{
    public function getLoader(): BuilderInterface;
    public function withLoader(BuilderInterface $loader): self;
}
```

#### Transform Repository

```php
namespace Kiboko\Contract\Configurator\Factory\Repository;

interface Transform extends RepositoryInterface
{
    public function getTransform(): BuilderInterface;
    public function withTransform(BuilderInterface $transform): self;
}
```

## Runtime System

### RuntimeInterface

```php
namespace Kiboko\Contract\Configurator;

interface RuntimeInterface
{
    /**
     * Get runtime configuration
     */
    public function configuration(): RuntimeConfigurationInterface;
    
    /**
     * Compile runtime configuration
     */
    public function compile(array $config): RuntimeRepositoryInterface;
}
```

### RuntimeConfigurationInterface

```php
namespace Kiboko\Contract\Configurator;

interface RuntimeConfigurationInterface extends ConfigurationInterface
{
    public function addPlugin(string $name, PluginConfigurationInterface $plugin): self;
    public function addFeature(string $name, FeatureConfigurationInterface $feature): self;
    public function addAction(string $name, ActionConfigurationInterface $action): self;
}
```

## Expression Language

### ExpressionLanguage Integration

```php
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

// Create expression language instance
$expressionLanguage = new ExpressionLanguage();

// Register custom providers
$expressionLanguage->registerProvider(new ArrayExpressionLanguageProvider());
$expressionLanguage->registerProvider(new StringExpressionLanguageProvider());

// Evaluate expressions
$result = $expressionLanguage->evaluate('@=env("DATABASE_URL")', [
    'env' => fn($key) => $_ENV[$key] ?? null
]);
```

### Custom Expression Providers

```php
namespace Kiboko\Component\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

class CustomExpressionProvider implements ExpressionFunctionProviderInterface
{
    public function getFunctions(): array
    {
        return [
            new ExpressionFunction(
                'custom_function',
                function ($arg) {
                    return sprintf('custom_function(%s)', $arg);
                },
                function ($arguments, $arg) {
                    return $this->customFunction($arg);
                }
            ),
        ];
    }
    
    private function customFunction($arg): mixed
    {
        // Custom function implementation
        return $arg;
    }
}
```

## Error Handling

### Exception Hierarchy

```php
namespace Kiboko\Contract\Configurator;

// Base exception interface
interface ConfigurationExceptionInterface extends \Throwable
{
}

// Invalid configuration exception
class InvalidConfigurationException extends \InvalidArgumentException 
    implements ConfigurationExceptionInterface
{
}

// Compilation exception
class CompilationFailureException extends \RuntimeException 
    implements ConfigurationExceptionInterface
{
}
```

### Error Handling Example

```php
try {
    $service = new Service();
    $config = $service->normalize($rawConfig);
    $repository = $service->compile($config);
} catch (InvalidConfigurationException $e) {
    // Handle configuration errors
    echo "Configuration error: " . $e->getMessage();
} catch (CompilationFailureException $e) {
    // Handle compilation errors
    echo "Compilation error: " . $e->getMessage();
}
```

## Usage Examples

### Basic Compiler Usage

```php
use Kiboko\Component\Satellite\Service;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

// Create compiler service
$compiler = new Service(new ExpressionLanguage());

// Load and normalize configuration
$config = [
    'satellites' => [
        [
            'label' => 'My Pipeline',
            'pipeline' => [
                'extractor' => [
                    'csv' => ['file_path' => 'input.csv']
                ],
                'loader' => [
                    'csv' => ['file_path' => 'output.csv']
                ]
            ]
        ]
    ]
];

$normalizedConfig = $compiler->normalize($config);

// Validate configuration
if (!$compiler->validate($normalizedConfig)) {
    throw new \RuntimeException('Invalid configuration');
}

// Compile to executable code
$repository = $compiler->compile($normalizedConfig);

// Generate PHP code
$prettyPrinter = new \PhpParser\PrettyPrinter\Standard();
$code = $prettyPrinter->prettyPrintFile([$repository->getNode()]);

echo $code;
```

### Plugin Registration

```php
use Kiboko\Component\Satellite\Service;
use Kiboko\Plugin\CSV\Service as CSVPlugin;
use Kiboko\Plugin\SQL\Service as SQLPlugin;

$compiler = new Service();

// Register plugins
$compiler->addPipelinePlugin(
    new \Kiboko\Contract\Configurator\Pipeline(name: 'csv'),
    new CSVPlugin()
);

$compiler->addPipelinePlugin(
    new \Kiboko\Contract\Configurator\Pipeline(name: 'sql'),
    new SQLPlugin()
);

// Now CSV and SQL plugins are available in configurations
```

### Custom Plugin Development

```php
use Kiboko\Contract\Configurator;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class MyCustomPlugin implements Configurator\PipelinePluginInterface
{
    public function interpreter(): ExpressionLanguage
    {
        return new ExpressionLanguage();
    }
    
    public function configuration(): Configurator\PluginConfigurationInterface
    {
        return new MyCustomConfiguration();
    }
    
    public function normalize(array $config): array
    {
        // Normalize configuration
        return $config;
    }
    
    public function validate(array $config): bool
    {
        // Validate configuration
        return true;
    }
    
    public function compile(array $config): Configurator\RepositoryInterface
    {
        // Compile to executable code
        return new MyCustomRepository($config);
    }
}
```

## Performance Considerations

### Memory Management

- Use generators for large datasets
- Implement lazy loading for plugins
- Cache compiled AST when possible

### Optimization Tips

```php
// Use specific compilation methods for better performance
$pipeline = $compiler->compilePipeline($config['pipeline']);
$workflow = $compiler->compileWorkflow($config['workflow']);

// Cache expression language instances
$expressionLanguage = new ExpressionLanguage();
$compiler = new Service($expressionLanguage);
```

This API reference provides the foundation for developers to work with the Gyroscops Compiler programmatically, whether extending it with custom plugins or integrating it into larger applications.