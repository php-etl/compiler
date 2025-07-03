# Architecture Decision Record (ADR) - Plugin Architecture Patterns

## ADR002: Plugin Architecture Patterns and Implementation Approaches

**Status:** Active  
**Date:** 2024-12-19  
**Authors:** Development Team  
**Reviewers:** Project Maintainers  

---

## Context

The Gyroscops Compiler supports a plugin architecture that enables extensibility for data connectivity, transformation, and processing. Through analysis of existing plugins, two distinct architectural patterns have emerged for implementing plugins:

1. **Flow Component Pattern**: Plugins that leverage external flow libraries (e.g., `php-etl/csv-flow`, `php-etl/sql-flow`)
2. **API Client Pattern**: Plugins that integrate third-party API clients with custom glue code (e.g., Akeneo plugin using `akeneo/api-php-client`)

As the plugin ecosystem grows, we need to establish clear guidelines for when to use each pattern, their trade-offs, and implementation best practices to ensure consistency, maintainability, and optimal developer experience.

### Current Plugin Landscape

**Flow Component Pattern Examples:**
- CSV Plugin: Uses `php-etl/csv-flow`
- SQL Plugin: Uses `php-etl/sql-flow`

**API Client Pattern Examples:**
- Akeneo Plugin: Uses `akeneo/api-php-client` with custom integration
- Shopify Plugin: Uses Shopify API client with custom integration

---

## Decision

### 1. Architectural Pattern Guidelines

#### 1.1 Flow Component Pattern (Preferred for Data Formats)

**When to Use:**
- File format processing (CSV, JSON, XML, Excel, etc.)
- Database connectivity (SQL, NoSQL)
- Standard protocol implementations (FTP, SFTP, HTTP)
- When a mature, well-maintained flow library exists

**Implementation Approach:**
```php
#[Configurator\Pipeline(
    name: 'csv',
    dependencies: [
        'php-etl/csv-flow:*',
    ],
    steps: [
        new Configurator\Pipeline\StepExtractor(),
        new Configurator\Pipeline\StepLoader(),
    ],
)]
final readonly class Service implements Configurator\PipelinePluginInterface
{
    public function compile(array $config): Configurator\RepositoryInterface
    {
        if (array_key_exists('extractor', $config)) {
            $extractorFactory = new Factory\Extractor($this->interpreter);
            return $extractorFactory->compile($config['extractor']);
        }
        // Similar for loader/transformer
    }
}
```

**Factory Implementation:**
```php
final readonly class Extractor implements Configurator\FactoryInterface
{
    public function compile(array $config): Repository\Extractor
    {
        $extractor = new Builder\Extractor(
            filePath: compileValueWhenExpression($this->interpreter, $config['file_path']),
            delimiter: $config['delimiter'] ?? null,
            // Other flow-specific parameters
        );
        
        return new Repository\Extractor($extractor);
    }
}
```

#### 1.2 API Client Pattern (Required for Third-Party APIs)

**When to Use:**
- Third-party SaaS API integration (Akeneo, Shopify, Salesforce, etc.)
- Complex authentication requirements (OAuth, JWT, custom tokens)
- API-specific features (pagination, rate limiting, webhooks)
- When no suitable flow library exists

**Implementation Approach:**
```php
#[Configurator\Pipeline(
    name: 'akeneo',
    dependencies: [
        'akeneo/api-php-client',
        'laminas/laminas-diactoros',
        'php-http/guzzle7-adapter',
    ],
    steps: [
        new Configurator\Pipeline\StepExtractor(),
        new Configurator\Pipeline\StepTransformer('lookup'),
        new Configurator\Pipeline\StepLoader(),
    ],
)]
final readonly class Service implements Configurator\PipelinePluginInterface
{
    public function compile(array $config): Configurator\RepositoryInterface
    {
        $clientFactory = new Factory\Client($this->interpreter);
        
        if (array_key_exists('extractor', $config)) {
            $extractorFactory = new Factory\Extractor($this->interpreter);
            $extractor = $extractorFactory->compile($config['extractor']);
            $client = $clientFactory->compile($config['client']);
            
            $extractor->getBuilder()->withClient($client->getBuilder()->getNode());
            $extractor->merge($client);
            
            return $extractor;
        }
        // Similar for loader/lookup
    }
}
```

### 2. Implementation Standards

#### 2.1 Common Requirements (Both Patterns)

**Service Class Structure:**
- Must implement `Configurator\PipelinePluginInterface`
- Must use `#[Configurator\Pipeline]` attribute with proper metadata
- Must support expression language integration
- Must provide proper error handling and validation

**Configuration Management:**
- Use Symfony Config component for schema definition
- Support expression language in configuration values
- Provide clear validation messages
- Follow consistent naming conventions

**Factory Pattern:**
- Separate factories for Extractor, Loader, and Transformer
- Implement `Configurator\FactoryInterface`
- Use builder pattern for code generation
- Support dependency injection

#### 2.2 Flow Component Pattern Specifics

**Dependencies:**
- Declare flow library dependencies in plugin attribute
- Use semantic versioning constraints
- Minimize additional dependencies

**Builder Integration:**
- Wrap flow components in custom builders
- Provide configuration mapping to flow library parameters
- Support flow-specific features (safe mode, error handling)

**Example Structure:**
```
src/
├── Service.php                 # Main plugin service
├── Configuration.php           # Root configuration
├── Configuration/
│   ├── Extractor.php          # Extractor configuration schema
│   └── Loader.php             # Loader configuration schema
├── Factory/
│   ├── Extractor.php          # Extractor factory
│   └── Loader.php             # Loader factory
├── Builder/
│   ├── Extractor.php          # Extractor builder (wraps flow)
│   └── Loader.php             # Loader builder (wraps flow)
└── Repository/
    ├── Extractor.php          # Extractor repository
    └── Loader.php             # Loader repository
```

#### 2.3 API Client Pattern Specifics

**Client Management:**
- Create dedicated client factory and builder
- Handle authentication and connection configuration
- Support multiple authentication methods
- Implement proper error handling for API failures

**Integration Layer:**
- Create glue code between API client and pipeline interfaces
- Handle API-specific concerns (pagination, rate limiting)
- Provide proper data transformation between API and pipeline formats
- Support API-specific features (webhooks, real-time updates)

**Example Structure:**
```
src/
├── Service.php                 # Main plugin service
├── Configuration.php           # Root configuration
├── Configuration/
│   ├── Client.php             # API client configuration
│   ├── Extractor.php          # Extractor configuration
│   └── Loader.php             # Loader configuration
├── Factory/
│   ├── Client.php             # API client factory
│   ├── Extractor.php          # Extractor factory
│   └── Loader.php             # Loader factory
├── Builder/
│   ├── Client.php             # API client builder
│   ├── Extractor.php          # Extractor builder
│   └── Loader.php             # Loader builder
└── Repository/
    ├── Client.php             # Client repository
    ├── Extractor.php          # Extractor repository
    └── Loader.php             # Loader repository
```

### 3. Decision Matrix

| Criteria                 | Flow Component Pattern               | API Client Pattern                     |
|--------------------------|--------------------------------------|----------------------------------------|
| **Development Speed**    | Fast (leverage existing libraries)   | Moderate (custom integration required) |
| **Maintenance Burden**   | Low (upstream library maintenance)   | High (custom code maintenance)         |
| **Feature Completeness** | Limited to flow library capabilities | Full API feature access                |
| **Testing Complexity**   | Low (mock flow interfaces)           | High (mock API responses)              |
| **Performance**          | Optimized by flow library            | Depends on implementation quality      |
| **Flexibility**          | Limited by flow library design       | High (custom implementation)           |
| **Documentation**        | Leverage flow library docs           | Requires comprehensive custom docs     |

---

## Consequences

### Positive Outcomes

#### Flow Component Pattern Benefits:
- **Faster Development**: Leverage existing, battle-tested libraries
- **Lower Maintenance**: Upstream libraries handle bug fixes and improvements
- **Consistent Behavior**: Standardized behavior across similar data sources
- **Better Performance**: Optimized implementations from specialized libraries
- **Reduced Testing**: Focus on configuration and integration testing

#### API Client Pattern Benefits:
- **Full Feature Access**: Complete access to API capabilities
- **Custom Optimization**: Tailor implementation to specific use cases
- **Direct Control**: Full control over error handling and edge cases
- **API Evolution**: Can adapt quickly to API changes
- **Specialized Features**: Support for API-specific features like webhooks

### Negative Outcomes

#### Flow Component Pattern Limitations:
- **Feature Constraints**: Limited by flow library capabilities
- **Dependency Risk**: Vulnerable to upstream library issues
- **Less Flexibility**: Cannot easily customize behavior
- **Version Conflicts**: Potential conflicts with flow library versions

#### API Client Pattern Challenges:
- **Higher Complexity**: More code to write, test, and maintain
- **Maintenance Burden**: Responsible for all bug fixes and improvements
- **Documentation Overhead**: Must document all custom functionality
- **Testing Complexity**: Requires comprehensive mocking and integration tests
- **Performance Risk**: Performance depends on implementation quality

### Migration Considerations

- **Existing Plugins**: No immediate changes required for existing plugins
- **New Plugins**: Must follow the decision matrix for pattern selection
- **Hybrid Approach**: Some plugins may benefit from combining both patterns
- **Documentation**: Update plugin development guide with these patterns

---

## Compliance

### Implementation Guidelines

#### For New Plugin Development:

1. **Pattern Selection**:
   - Evaluate available flow libraries first
   - Choose Flow Component Pattern when suitable library exists
   - Use API Client Pattern for third-party API integration
   - Document pattern choice rationale in plugin README

2. **Code Quality Standards**:
   - Follow PSR-12 coding standards
   - Achieve minimum 90% test coverage
   - Use static analysis tools (PHPStan level 8)
   - Provide comprehensive documentation

3. **Configuration Standards**:
   - Use consistent naming conventions
   - Support expression language where appropriate
   - Provide clear validation messages
   - Include usage examples in documentation

#### For Existing Plugin Maintenance:

1. **Pattern Consistency**: Maintain existing pattern unless major refactoring
2. **Incremental Improvements**: Apply standards during regular maintenance
3. **Documentation Updates**: Update documentation to reflect current patterns
4. **Migration Planning**: Plan migration to preferred patterns during major versions

### Review Process

- **New Plugins**: Must be reviewed against these patterns before approval
- **Pattern Deviations**: Require architectural review and justification
- **Documentation**: Must include pattern choice explanation
- **Testing**: Must demonstrate pattern-appropriate testing strategy

---

## Review and Updates

**Next Review Date:** 2025-06-19  
**Review Frequency:** Semi-annually or when significant plugin ecosystem changes occur  
**Update Triggers:**
- New flow libraries become available
- Significant changes in API client landscape
- Performance or maintenance issues with current patterns
- Community feedback on plugin development experience

**Stakeholders:**
- Plugin developers
- Core maintainers
- Community contributors
- End users providing feedback

---

## References

- [Plugin Development Guide](../documentation/content/compiler/plugin-development.en.md)
- [Configurator Contracts](https://github.com/php-etl/configurator-contracts)
- [CSV Flow Library](https://github.com/php-etl/csv-flow)
- [SQL Flow Library](https://github.com/php-etl/sql-flow)
- [Akeneo API Client](https://github.com/akeneo/api-php-client)
- [ADR001: Maintenance Guidelines](ADR001-maintenance-guidelines.md)