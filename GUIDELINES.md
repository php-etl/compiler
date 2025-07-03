# Project guidelines

Welcome to the Gyroscops Compiler project! These guidelines are designed to help new contributors (especially junior developers) understand how to effectively contribute to this sophisticated ETL platform.

## 🎯 What is Gyroscops Compiler?

Gyroscops Compiler is a **data processing and ETL (Extract, Transform, Load) platform** that:
- Generates PHP lambda functions for data processing pipelines
- Compiles YAML configurations into executable microservices
- Manages plugins for various data sources and destinations
- Orchestrates data workflows in cloud environments

## 📋 Before You Start

### Prerequisites
- **PHP 8.2+** (strict requirement)
- **Composer** for dependency management
- **Docker** for containerization
- **Git** with subtree knowledge (see [Development Workflow](#development-workflow))
- Basic understanding of **Symfony components**
- Familiarity with **YAML configuration**

### Required Reading
1. **[ADR000](architecture/ADR000-adr-management-process.md)** - ADR Management Process
2. **[ADR001](architecture/ADR001-maintenance-guidelines.md)** - Architecture and Maintenance Guidelines
3. **[README.md](README.md)** - Project overview

## 🏗️ Project Architecture

### Repository Structure
```
├── compiler/           # Satellite core compiler
├── plugins/           # Data source/destination plugins
├── toolbox/           # Development utilities
├── dockerfile/        # Docker configuration
├── phpspec-extension/ # Testing extensions
├── phpunit-extension/ # Testing extensions
├── tools/            # Additional tools (fast-map, etc.)
├── expression-language/ # Expression language extensions
├── architecture/      # Architecture Decision Records
└── bin/              # Development scripts
```

### Key Concepts
- **Monorepo with Git Subtrees**: Multiple repositories managed as one
- **Plugin Architecture**: Extensible system for data sources/destinations
- **Configuration-Driven**: YAML configs compiled to PHP code
- **Microservice Generation**: Creates containerized data processing services

## 🚀 Getting Started

### 1. Environment Setup
```bash
# Clone the repository
git clone <repository-url>
cd gyroscops-compiler

# Install dependencies
composer install

# Verify PHP version
php --version  # Should be 8.2+
```

### 2. Understanding the Codebase
```bash
# Explore the main compiler
ls -la compiler/src/

# Check available plugins
ls -la plugins/

# Review development scripts
ls -la bin/
```

### 3. Run Tests
```bash
# Run PHPSpec tests
vendor/bin/phpspec run

# Run PHPUnit tests
vendor/bin/phpunit
```

## 💻 Development Workflow

### Git Subtree Management
This project uses **git subtrees** to manage multiple repositories. **Important**: Never use regular git commands on subtree directories.

#### Pulling Changes
```bash
# Pull all changes from sub-repositories
./bin/pull-all.sh

# This updates:
# - compiler/ (from satellite repo)
# - plugins/* (from individual plugin repos)
# - toolbox/ (from satellite-toolbox repo)
# - And more...
```

#### Pushing Changes
```bash
# Push compiler changes
./bin/push-compiler.sh

# Push specific plugin changes
./bin/push-plugin.sh <plugin-name>

# Example: Push shopify plugin changes
./bin/push-plugin.sh shopify

# Push everything
./bin/push-all.sh
```

### Branch Management
- **Main branch**: Primary development branch
- **Feature branches**: Create in individual repositories when needed
- **Squash commits**: When merging subtrees

## 🔌 Plugin Development

### Plugin Structure
Every plugin must implement `Configurator\PipelinePluginInterface`:

```php
<?php

declare(strict_types=1);

namespace Kiboko\Plugin\YourPlugin;

use Kiboko\Contract\Configurator;

#[Configurator\Pipeline(
    name: 'your-plugin',
    dependencies: [
        'vendor/package',
    ],
    steps: [
        new Configurator\Pipeline\StepExtractor(),
        new Configurator\Pipeline\StepLoader(),
    ],
)]
final readonly class Service implements Configurator\PipelinePluginInterface
{
    // Implementation...
}
```

### Required Methods
1. **`interpreter()`**: Returns ExpressionLanguage instance
2. **`configuration()`**: Returns plugin configuration interface
3. **`normalize(array $config)`**: Normalizes configuration
4. **`validate(array $config)`**: Validates configuration
5. **`compile(array $config)`**: Compiles to executable code

### Plugin Naming Convention
- Repository: `{name}-plugin` (e.g., `shopify-plugin`)
- Directory: `plugins/{name}/` (e.g., `plugins/shopify/`)

## 📝 Code Standards

### PHP Standards
- **Follow PER** (PHP Evolving Recommendation) coding standards
- **Strict typing**: Always use `declare(strict_types=1);`
- **PHP 8.2+ features**: Use modern syntax and features
- **Readonly classes**: Use when appropriate
- **Type hints**: Always provide proper type hints

### Example Code Structure
```php
<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Example;

final readonly class ExampleClass
{
    public function __construct(
        private string $property,
    ) {}

    public function process(array $data): array
    {
        // Implementation
        return $data;
    }
}
```

### Configuration Standards
- **YAML format**: Use YAML for all configurations
- **JSON schema validation**: Implement proper validation
- **Expression language**: Support dynamic configurations

### Error Handling
```php
try {
    $result = $this->processor->processConfiguration($this->configuration, $config);
} catch (Symfony\InvalidTypeException|Symfony\InvalidConfigurationException $exception) {
    throw new Configurator\InvalidConfigurationException(
        $exception->getMessage(), 
        0, 
        $exception
    );
}
```

## 🧪 Testing Requirements

### Testing Frameworks
- **PHPSpec**: For behavior-driven testing
- **PHPUnit**: For integration tests

### Test Structure
```
tests/
├── unit/           # Unit tests
├── integration/    # Integration tests
└── fixtures/       # Test data
```

### Writing Tests
```php
// PHPSpec example
class ServiceSpec extends ObjectBehavior
{
    function it_should_validate_configuration()
    {
        $config = ['key' => 'value'];
        $this->validate($config)->shouldReturn(true);
    }
}

// PHPUnit example
class ServiceTest extends TestCase
{
    public function testConfigurationValidation(): void
    {
        $service = new Service();
        $config = ['key' => 'value'];
        
        $this->assertTrue($service->validate($config));
    }
}
```

## 📚 Documentation Standards

### Code Documentation
- **PHPDoc blocks**: For all public methods
- **Inline comments**: For complex logic
- **README files**: For each component

### Example PHPDoc
```php
/**
 * Compiles the configuration into executable code.
 *
 * @param array $config The plugin configuration
 * @return Factory\Repository\Extractor|Factory\Repository\Loader
 * @throws Configurator\ConfigurationExceptionInterface
 */
public function compile(array $config): Factory\Repository\Extractor|Factory\Repository\Loader
{
    // Implementation
}
```

## 🐳 Docker and Containerization

### Docker Standards
- **Multi-stage builds**: For optimization
- **PHP 8.2+ base images**: Always use latest supported version
- **Security best practices**: Follow Docker security guidelines

### Example Dockerfile Structure
```dockerfile
FROM php:8.2-cli AS base
# Base setup

FROM base AS dependencies
# Install dependencies

FROM dependencies AS final
# Final image
```

## 🔄 Contribution Process

### 1. Issue Identification
- Check existing issues
- Create new issue if needed
- Discuss approach with maintainers

### 2. Development
- Fork the repository (if external contributor)
- Create feature branch in appropriate sub-repository
- Follow coding standards
- Write tests
- Update documentation

### 3. Testing
```bash
# Run all tests
composer test

# Run specific test suites
vendor/bin/phpspec run
vendor/bin/phpunit
```

### 4. Pull Request
- Create PR with clear description
- Reference related issues
- Ensure CI passes
- Request review from maintainers

### 5. Review Process
- **Minimum review period**: 48 hours
- **Required reviewers**: At least 2 maintainers
- Address feedback promptly
- Maintain code quality

## 🚨 Common Pitfalls

### Git Subtree Issues
❌ **Don't do this:**
```bash
cd plugins/shopify
git add .
git commit -m "Changes"  # This breaks subtree structure!
```

✅ **Do this instead:**
```bash
# Make changes in plugins/shopify/
git add plugins/shopify/
git commit -m "Update shopify plugin"
./bin/push-plugin.sh shopify
```

### Configuration Errors
❌ **Don't do this:**
```php
// Missing strict types
namespace Kiboko\Plugin\Example;

class Service // Not readonly, not final
{
    public function validate($config) // No type hints
    {
        return true; // No actual validation
    }
}
```

✅ **Do this instead:**
```php
<?php

declare(strict_types=1);

namespace Kiboko\Plugin\Example;

final readonly class Service implements Configurator\PipelinePluginInterface
{
    public function validate(array $config): bool
    {
        try {
            $this->processor->processConfiguration($this->configuration, $config);
            return true;
        } catch (Symfony\InvalidTypeException|Symfony\InvalidConfigurationException) {
            return false;
        }
    }
}
```

## 🆘 Getting Help

### Resources
- **Architecture docs**: `architecture/` directory
- **Plugin examples**: `plugins/` directory
- **Development scripts**: `bin/` directory

### Communication
- Create GitHub issues for bugs/features
- Use discussions for questions
- Follow the established ADR process for architectural decisions

### Debugging
```bash
# Check subtree status
git log --oneline --graph

# Verify plugin structure
ls -la plugins/your-plugin/

# Test configuration
vendor/bin/phpspec run spec/YourPluginSpec.php
```

## 📋 Checklist for New Contributors

### Before First Contribution
- [ ] Read all ADR documents
- [ ] Set up development environment
- [ ] Run existing tests successfully
- [ ] Understand git subtree workflow
- [ ] Review existing plugin implementations

### For Each Contribution
- [ ] Follow PER coding standards
- [ ] Include `declare(strict_types=1);`
- [ ] Add proper type hints
- [ ] Write comprehensive tests
- [ ] Update documentation
- [ ] Test locally before pushing
- [ ] Use correct git subtree workflow

### Plugin Development
- [ ] Implement `PipelinePluginInterface`
- [ ] Add plugin attribute with metadata
- [ ] Create configuration class
- [ ] Implement all required methods
- [ ] Add comprehensive tests
- [ ] Document configuration options

## 🎉 Welcome to the Team!

Remember: **Quality over speed**. It's better to take time to understand the architecture and follow the established patterns than to rush and create technical debt.

The Gyroscops Compiler project values:
- **Clean, maintainable code**
- **Comprehensive testing**
- **Clear documentation**
- **Collaborative development**

Happy coding! 🚀

---

**Last Updated**: 2024-12-19  
**Maintainer**: Grégory Planchat
**Version**: 1.0.0