---
title: "Compiler"
date: 2024-01-01T00:00:00Z
draft: false
weight: 3
description: "Comprehensive documentation for the Gyroscops Compiler"
---

# Gyroscops Compiler

The Gyroscops Compiler is the core component that transforms YAML configuration files into executable PHP code for data processing pipelines. It provides a powerful framework for building ETL (Extract, Transform, Load) operations with support for various data sources, transformations, and destinations.

## Overview

The compiler takes declarative YAML configurations and generates optimized PHP code that can be executed as standalone applications or deployed as microservices. It supports multiple runtime environments and provides extensive plugin architecture for connectivity and data processing.

## Key Features

- **Configuration-driven**: Define data pipelines using simple YAML syntax
- **Code generation**: Compiles configurations into optimized PHP code
- **Plugin architecture**: Extensible system for data sources and destinations
- **Multiple runtimes**: Support for different execution environments
- **Cloud-ready**: Built for containerization and cloud deployment
- **Expression language**: Dynamic configuration with expression support

## Documentation Sections

- [Architecture Overview](architecture/) - Understanding the compiler's internal structure
- [Configuration Schema](configuration/) - Complete reference for YAML configuration
- [API Reference](api/) - Detailed API documentation for developers
- [Plugin Development](plugin-development/) - Guide for creating custom plugins

## Quick Start

```yaml
# example-pipeline.yaml
satellites:
  - label: "My Data Pipeline"
    pipeline:
      extractor:
        csv:
          file_path: "input.csv"
      loader:
        csv:
          file_path: "output.csv"
```

Compile and execute:

```bash
# Compile the configuration
bin/satellite compile config=example-pipeline.yaml

# Execute the generated pipeline
php pipeline.php
```