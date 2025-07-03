# Architecture Decision Record (ADR) - Gyroscops Compiler Maintenance Guidelines

## ADR001: Gyroscops Compiler Architecture and Maintenance Guidelines

**Status:** Active  
**Date:** 2024-12-19  
**Authors:** Development Team  
**Reviewers:** Project Maintainers

---

## Context

Gyroscops Compiler is a sophisticated ETL platform that uses a monorepo structure with git subtrees to manage multiple related repositories. The project consists of a core compiler (Satellite), numerous plugins for different data sources, and supporting tools. This ADR establishes architectural decisions and maintenance guidelines to ensure consistency, quality, and scalability.

---

## Decision

### 1. Repository Structure and Management

#### 1.1 Monorepo with Git Subtrees
- **Decision**: Use git subtrees to manage multiple sub-repositories within a single monorepo
- **Rationale**: Enables centralized development while maintaining individual repository independence
- **Implementation**:
    - Core components in dedicated directories (`compiler/`, `plugins/`, `toolbox/`, etc.)
    - Automated scripts for synchronization (`bin/pull-all.sh`, `bin/push-all.sh`)
    - Individual plugin repositories maintained as subtrees

#### 1.2 Directory Structure Standards
```
├── compiler/           # Satellite core compiler
├── plugins/           # Data source/destination plugins
├── toolbox/           # Development utilities
├── dockerfile/        # Docker configuration
├── phpspec-extension/ # Testing extensions
├── phpunit-extension/ # Testing extensions
├── tools/            # Additional tools (fast-map, etc.)
├── expression-language/ # Expression language extensions
└── bin/              # Development scripts
```

### 2. Plugin Architecture

#### 2.1 Plugin Interface Compliance
- **Decision**: All plugins must implement `Configurator\PipelinePluginInterface`
- **Requirements**:
    - Use PHP 8.2+ attributes for plugin metadata
    - Implement required methods: `interpreter()`, `configuration()`, `normalize()`, `validate()`, `compile()`
    - Follow the established factory pattern for builders

#### 2.2 Plugin Naming Convention
- **Decision**: Plugins follow the pattern `{name}-plugin` in repositories and `plugins/{name}/` in monorepo
- **Example**: `shopify-plugin` repository → `plugins/shopify/` directory

#### 2.3 Plugin Dependencies
- **Decision**: Plugin dependencies must be declared in the plugin attribute
- **Requirements**:
    - List all external dependencies in the `#[Configurator\Pipeline]` attribute
    - Specify supported steps (extractor, loader, transformer, lookup)

### 3. Development Workflow

#### 3.1 Synchronization Scripts
- **Decision**: Use provided scripts for repository synchronization
- **Guidelines**:
    - `./bin/pull-all.sh` - Pull changes from all sub-repositories
    - `./bin/push-plugin.sh <plugin-name>` - Push changes to specific plugin
    - `./bin/push-compiler.sh` - Push compiler changes
    - Always test locally before pushing changes

#### 3.2 Branch Management
- **Decision**: Use `main` branch as the primary development branch
- **Requirements**:
    - All subtree operations target the `main` branch
    - Feature branches should be created in individual repositories when needed
    - Squash commits when merging subtrees

### 4. Code Standards

#### 4.1 PHP Standards
- **Decision**: Follow PER (PHP Evolving Recommendation) coding standards with strict typing
- **Rationale**: PER represents the evolving PHP coding standards and supersedes PSR-12, providing more up-to-date guidelines for modern PHP development
- **Requirements**:
    - Use `declare(strict_types=1);` in all PHP files
    - PHP 8.2+ minimum version
    - Use readonly classes where appropriate
    - Implement proper type hints for all methods
    - Follow PER formatting and style guidelines
    - Use modern PHP features and syntax patterns as defined in PER

#### 4.2 Configuration Standards
- **Decision**: Use YAML for configuration with JSON schema validation
- **Requirements**:
    - Provide configuration classes implementing `PluginConfigurationInterface`
    - Use Symfony Config component for validation
    - Support expression language for dynamic configurations

#### 4.3 Error Handling
- **Decision**: Use custom exception hierarchy
- **Requirements**:
    - Catch Symfony configuration exceptions and wrap in `Configurator\InvalidConfigurationException`
    - Provide meaningful error messages
    - Include previous exceptions for debugging

### 5. Testing Requirements

#### 5.1 Testing Framework
- **Decision**: Use PHPSpec for behavior-driven testing and PHPUnit for integration tests
- **Requirements**:
    - Custom extensions available in `phpspec-extension/` and `phpunit-extension/`
    - All plugins must have comprehensive test coverage
    - Test configuration validation and compilation processes

#### 5.2 Test Structure
- **Requirements**:
    - Unit tests for individual components
    - Integration tests for plugin compilation
    - Configuration validation tests

### 6. Documentation Standards

#### 6.1 README Requirements
- **Decision**: Each component must have comprehensive README documentation
- **Requirements**:
    - Purpose and functionality description
    - Installation and usage instructions
    - Configuration examples
    - API documentation for public interfaces

#### 6.2 Code Documentation
- **Requirements**:
    - PHPDoc blocks for all public methods
    - Inline comments for complex logic
    - Configuration schema documentation

### 7. Containerization

#### 7.1 Docker Standards
- **Decision**: Use Docker for containerization with standardized Dockerfiles
- **Requirements**:
    - Dockerfiles in `dockerfile/` directory
    - Multi-stage builds for optimization
    - PHP 8.2+ base images
    - Security best practices

### 8. Expression Language

#### 8.1 Extension Management
- **Decision**: Expression language extensions managed as subtrees
- **Structure**: `expression-language/{type}/` (e.g., `expression-language/string/`, `expression-language/array/`)
- **Requirements**:
    - Follow Symfony ExpressionLanguage provider pattern
    - Register providers in plugin configurations

---

## Consequences

### Positive
- **Consistency**: Standardized structure and practices across all components
- **Maintainability**: Clear guidelines for code organization and development workflow
- **Scalability**: Plugin architecture allows easy extension
- **Quality**: Testing and documentation requirements ensure high code quality
- **Modern Standards**: PER adoption ensures the codebase follows the latest PHP coding standards

### Negative
- **Complexity**: Git subtree management requires understanding of the workflow
- **Learning Curve**: New contributors need to understand the monorepo structure and PER standards
- **Synchronization**: Manual synchronization between repositories required
- **Migration**: Existing code may need to be updated to comply with PER standards

---

## Compliance

### For Maintainers
1. **Code Reviews**: Ensure all PRs comply with these guidelines and PER standards
2. **Plugin Validation**: Verify new plugins follow the established patterns
3. **Documentation**: Maintain up-to-date documentation
4. **Testing**: Ensure adequate test coverage for all changes
5. **Standards Enforcement**: Ensure PER compliance in all code contributions

### For Contributors
1. **Read Guidelines**: Understand the architecture and PER standards before contributing
2. **Follow Patterns**: Use existing plugins as templates for new development
3. **Test Thoroughly**: Run tests locally before submitting PRs
4. **Document Changes**: Update documentation for any architectural changes
5. **Code Formatting**: Ensure all code follows PER formatting guidelines

---

## Review and Updates

This ADR should be reviewed quarterly and updated as the project evolves. Any significant architectural changes should result in a new ADR or amendment to this document. PER standard updates should be evaluated and incorporated as they become available.

**Next Review Date:** 2025-03-19

---

## References

- [PER (PHP Evolving Recommendation)](https://www.php-fig.org/per/) - Current PHP coding standards
- [PHP-FIG Standards](https://www.php-fig.org/) - PHP Framework Interop Group standards
- [Symfony Coding Standards](https://symfony.com/doc/current/contributing/code/standards.html) - Additional guidelines for Symfony components
