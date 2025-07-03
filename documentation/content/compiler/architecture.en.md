---
title: "Architecture Overview"
date: 2024-01-01T00:00:00Z
draft: false
weight: 1
description: "Understanding the Gyroscops Compiler's internal architecture and components"
---

# Compiler Architecture Overview

The Gyroscops Compiler follows a modular architecture designed for extensibility, performance, and maintainability. This document provides a comprehensive overview of the compiler's internal structure and how its components work together.

## Core Components

### 1. Service Layer (`Service.php`)

The main `Service` class acts as the central orchestrator and implements the `FactoryInterface`. It provides the primary API for:

- **Configuration Processing**: Normalizing and validating YAML configurations
- **Compilation**: Converting configurations into executable PHP code
- **Component Registration**: Managing adapters, runtimes, plugins, features, and actions
- **Code Generation**: Producing optimized PHP code for different satellite types

Key methods:
- `compile()`: Main compilation entry point
- `compileWorkflow()`: Generates workflow satellites
- `compilePipeline()`: Generates pipeline satellites
- `compileApi()`: Generates API satellites
- `compileHook()`: Generates HTTP hook satellites
- `compileActionJob()`: Generates action satellites

### 2. Configuration System

The configuration system uses Symfony's Config component to define and validate YAML structures:

#### Configuration Class (`Configuration.php`)
- Manages configuration tree building
- Handles adapters, runtimes, plugins, features, and actions
- Provides backward compatibility support
- Validates configuration dependencies and exclusions

#### Key Configuration Concepts:
- **Satellites**: Top-level configuration containers
- **Adapters**: Define how satellites connect to external systems
- **Runtimes**: Specify execution environments (Docker, Kubernetes, etc.)
- **Plugins**: Provide connectivity to data sources and destinations
- **Features**: Add functionality like logging, state management
- **Actions**: Define executable operations

### 3. Builder System

The builder system generates PHP Abstract Syntax Trees (AST) using `nikic/php-parser`:

#### Builder Components:
- **Repository Builders**: Generate complete satellite implementations
- **Component Builders**: Create specific functionality (extractors, loaders, transformers)
- **Code Generators**: Convert AST to executable PHP code

#### Builder Types:
- `Workflow`: Multi-step data processing workflows
- `Pipeline`: Single-step ETL operations
- `API`: HTTP API endpoints for data access
- `Hook`: HTTP webhooks for event-driven processing
- `Action`: Standalone executable operations

### 4. Plugin Architecture

The plugin system provides extensibility for data connectivity and processing:

#### Plugin Types:
- **Pipeline Plugins**: Data extractors, loaders, and transformers
- **Feature Plugins**: Cross-cutting concerns (logging, monitoring, state)
- **Action Plugins**: Executable operations and utilities

#### Plugin Interface:
```php
interface PipelinePluginInterface
{
    public function interpreter(): ExpressionLanguage;
    public function configuration(): PluginConfigurationInterface;
    public function normalize(array $config): array;
    public function validate(array $config): bool;
    public function compile(array $config): RepositoryInterface;
}
```

### 5. Runtime System

Runtimes define how compiled satellites are executed:

#### Runtime Types:
- **Docker**: Containerized execution
- **Kubernetes**: Cloud-native orchestration
- **Local**: Direct PHP execution
- **Cloud**: Managed cloud execution

#### Runtime Features:
- Environment configuration
- Resource management
- Scaling policies
- Monitoring integration

### 6. Expression Language

The expression language system enables dynamic configuration:

#### Features:
- Variable interpolation: `@=env("DATABASE_URL")`
- Data transformation: `@=input["field"] | upper`
- Conditional logic: `@=condition ? value1 : value2`
- Function calls: `@=join(",", array)`

#### Expression Providers:
- Array functions
- String functions
- Date/time functions
- Custom domain-specific functions

## Compilation Process

### 1. Configuration Loading
```
YAML Config → ConfigLoader → Normalized Array
```

### 2. Validation
```
Normalized Config → Configuration::validate() → Validated Config
```

### 3. Compilation
```
Validated Config → Service::compile() → Repository (AST)
```

### 4. Code Generation
```
Repository (AST) → PrettyPrinter → Executable PHP Code
```

## Data Flow Architecture

### Pipeline Data Flow
```
Source → Extractor → [Transformers] → Loader → Destination
```

### Workflow Data Flow
```
Input → Step 1 → Step 2 → ... → Step N → Output
```

### API Data Flow
```
HTTP Request → Router → Handler → Response
```

### Hook Data Flow
```
HTTP Webhook → Validator → Processor → Response
```

## Dependency Injection

The compiler uses Symfony's DependencyInjection component for:

- Service registration and configuration
- Plugin discovery and loading
- Runtime environment setup
- Feature integration

## Error Handling

### Configuration Errors
- Schema validation errors
- Missing required fields
- Invalid field values
- Dependency conflicts

### Compilation Errors
- Plugin compilation failures
- Code generation errors
- Runtime compatibility issues

### Runtime Errors
- Execution failures
- Resource constraints
- Network connectivity issues

## Performance Considerations

### Compilation Optimization
- AST caching for repeated compilations
- Lazy loading of plugins and features
- Optimized code generation patterns

### Runtime Optimization
- Minimal memory footprint
- Efficient data streaming
- Connection pooling
- Batch processing support

## Extension Points

### Custom Plugins
- Implement `PipelinePluginInterface`
- Register with plugin discovery system
- Provide configuration schema

### Custom Runtimes
- Implement `RuntimeInterface`
- Define execution environment
- Handle resource management

### Custom Features
- Implement `FeatureInterface`
- Integrate with existing pipelines
- Provide cross-cutting functionality

## Security Considerations

### Configuration Security
- Input validation and sanitization
- Expression language sandboxing
- Credential management
- Access control

### Runtime Security
- Container isolation
- Network security
- Resource limits
- Audit logging

This architecture enables the Gyroscops Compiler to be highly extensible while maintaining performance and reliability for enterprise-grade data processing workloads.