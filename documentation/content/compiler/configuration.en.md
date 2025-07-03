---
title: "Configuration Schema"
date: 2024-01-01T00:00:00Z
draft: false
weight: 2
description: "Complete reference for YAML configuration schema"
---

# Configuration Schema Reference

This document provides a comprehensive reference for the Gyroscops Compiler YAML configuration schema. The configuration defines how satellites (data processing units) are structured and executed.

## Root Configuration Structure

```yaml
# Root configuration key
etl:
  # Version specification (optional)
  version: "1.0"
  
  # Satellites array - main configuration container
  satellites:
    - label: "Satellite Name"
      # Satellite configuration...
```

## Satellites Configuration

Satellites are the primary execution units. Each satellite represents a complete data processing workflow.

### Basic Satellite Structure

```yaml
satellites:
  - label: "My Data Pipeline"           # Required: Human-readable name
    # Runtime configuration
    docker:                            # Docker runtime
      # Docker-specific configuration
    
    # OR Kubernetes runtime
    kubernetes:
      # Kubernetes-specific configuration
    
    # Satellite type (one of the following)
    pipeline:                          # ETL Pipeline
      # Pipeline configuration
    
    workflow:                          # Multi-step workflow
      # Workflow configuration
    
    api:                              # HTTP API
      # API configuration
    
    hook:                             # HTTP Hook
      # Hook configuration
    
    action:                           # Standalone action
      # Action configuration
```

## Runtime Configurations

### Docker Runtime

```yaml
docker:
  image: "php:8.2-cli"                # Base Docker image
  workdir: "/app"                     # Working directory
  user: "1000:1000"                   # User ID:Group ID
  environment:                        # Environment variables
    - "APP_ENV=production"
    - "DATABASE_URL=@=env('DATABASE_URL')"
  volumes:                            # Volume mounts
    - "./data:/app/data:ro"
  ports:                              # Port mappings
    - "8080:80"
  networks:                           # Docker networks
    - "app-network"
  labels:                             # Docker labels
    app.name: "my-pipeline"
  restart_policy: "unless-stopped"    # Restart policy
  memory_limit: "512m"                # Memory limit
  cpu_limit: "0.5"                    # CPU limit
```

### Kubernetes Runtime

```yaml
kubernetes:
  namespace: "data-processing"        # Kubernetes namespace
  deployment:
    replicas: 3                       # Number of replicas
    strategy:                         # Deployment strategy
      type: "RollingUpdate"
      rolling_update:
        max_surge: 1
        max_unavailable: 0
  pod:
    image: "my-registry/pipeline:latest"
    image_pull_policy: "Always"
    resources:                        # Resource requirements
      requests:
        memory: "256Mi"
        cpu: "100m"
      limits:
        memory: "512Mi"
        cpu: "500m"
    environment:                      # Environment variables
      - name: "APP_ENV"
        value: "production"
      - name: "DATABASE_URL"
        value_from:
          secret_key_ref:
            name: "app-secrets"
            key: "database-url"
  service:                            # Kubernetes service
    type: "ClusterIP"
    ports:
      - port: 80
        target_port: 8080
  ingress:                            # Ingress configuration
    enabled: true
    host: "pipeline.example.com"
    tls: true
```

## Satellite Types

### Pipeline Configuration

Pipelines define ETL (Extract, Transform, Load) operations:

```yaml
pipeline:
  # Data extraction
  extractor:
    csv:                              # CSV extractor
      file_path: "input.csv"
      delimiter: ","
      enclosure: '"'
      escape: "\\"
      headers: true
    
    # OR database extractor
    sql:
      connection:
        dsn: "@=env('DATABASE_URL')"
      query: "SELECT * FROM users WHERE active = 1"
    
    # OR API extractor
    akeneo:
      client:
        api_url: "https://demo.akeneo.com"
        client_id: "client_id"
        secret: "secret"
        username: "username"
        password: "password"
      extractor:
        type: "product"
        method: "all"
  
  # Data transformation (optional)
  transform:
    map:                              # Field mapping
      - field: "[name]"
        expression: "input['full_name']"
      - field: "[email]"
        expression: "lower(input['email_address'])"
    
    filter:                           # Data filtering
      condition: "input['status'] == 'active'"
  
  # Data loading
  loader:
    csv:                              # CSV loader
      file_path: "output.csv"
      delimiter: ","
      enclosure: '"'
    
    # OR database loader
    sql:
      connection:
        dsn: "@=env('TARGET_DATABASE_URL')"
      table: "processed_users"
      mode: "insert"                  # insert, update, upsert
  
  # Error handling
  rejection:
    file_path: "rejected.csv"
  
  # State management
  state:
    file_path: "pipeline.state"
```

### Workflow Configuration

Workflows define multi-step data processing:

```yaml
workflow:
  jobs:
    - label: "Extract Users"
      pipeline:
        extractor:
          sql:
            connection:
              dsn: "@=env('SOURCE_DB')"
            query: "SELECT * FROM users"
        loader:
          csv:
            file_path: "users.csv"
    
    - label: "Transform Users"
      pipeline:
        extractor:
          csv:
            file_path: "users.csv"
        transform:
          map:
            - field: "[processed_at]"
              expression: "now()"
        loader:
          csv:
            file_path: "processed_users.csv"
    
    - label: "Load to Target"
      pipeline:
        extractor:
          csv:
            file_path: "processed_users.csv"
        loader:
          sql:
            connection:
              dsn: "@=env('TARGET_DB')"
            table: "users"
```

### API Configuration

APIs expose data through HTTP endpoints:

```yaml
api:
  endpoints:
    - path: "/users"
      methods: ["GET", "POST"]
      extractor:
        sql:
          connection:
            dsn: "@=env('DATABASE_URL')"
          query: "SELECT * FROM users WHERE id = @=request['id']"
      
    - path: "/users/{id}"
      methods: ["GET", "PUT", "DELETE"]
      extractor:
        sql:
          connection:
            dsn: "@=env('DATABASE_URL')"
          query: "SELECT * FROM users WHERE id = @=path['id']"
  
  middleware:
    - authentication:
        type: "bearer"
        secret: "@=env('JWT_SECRET')"
    - rate_limiting:
        requests_per_minute: 100
    - cors:
        allowed_origins: ["*"]
        allowed_methods: ["GET", "POST", "PUT", "DELETE"]
```

### Hook Configuration

Hooks process incoming HTTP webhooks:

```yaml
hook:
  path: "/webhook"
  methods: ["POST"]
  
  # Webhook validation
  validation:
    signature:
      header: "X-Hub-Signature-256"
      secret: "@=env('WEBHOOK_SECRET')"
      algorithm: "sha256"
  
  # Data processing
  pipeline:
    extractor:
      webhook:
        payload_path: "body"
    transform:
      map:
        - field: "[processed_at]"
          expression: "now()"
        - field: "[source]"
          expression: "'webhook'"
    loader:
      sql:
        connection:
          dsn: "@=env('DATABASE_URL')"
        table: "webhook_events"
  
  # Response configuration
  response:
    status: 200
    headers:
      Content-Type: "application/json"
    body: '{"status": "processed"}'
```

### Action Configuration

Actions define standalone executable operations:

```yaml
action:
  type: "cleanup"
  schedule: "0 2 * * *"               # Cron expression
  
  steps:
    - sql:
        connection:
          dsn: "@=env('DATABASE_URL')"
        query: "DELETE FROM logs WHERE created_at < NOW() - INTERVAL 30 DAY"
    
    - filesystem:
        operation: "delete"
        path: "/tmp/cache/*"
        pattern: "*.tmp"
        older_than: "7 days"
```

## Feature Configuration

Features provide cross-cutting functionality:

### Logging

```yaml
logger:
  type: "file"                        # file, stderr, syslog
  path: "/var/log/pipeline.log"
  level: "info"                       # debug, info, warning, error
  format: "json"                      # json, line
  
  # OR structured logging
  monolog:
    handlers:
      - type: "stream"
        path: "php://stderr"
        level: "info"
      - type: "rotating_file"
        path: "/var/log/app.log"
        max_files: 30
```

### State Management

```yaml
state:
  type: "file"                        # file, redis, database
  path: "/var/state/pipeline.state"
  
  # OR Redis state
  redis:
    host: "redis.example.com"
    port: 6379
    database: 0
    prefix: "pipeline:"
  
  # OR Database state
  database:
    dsn: "@=env('STATE_DATABASE_URL')"
    table: "pipeline_state"
```

### Rejection Handling

```yaml
rejection:
  type: "file"                        # file, database, queue
  path: "/var/rejected/items.csv"
  
  # OR Database rejection
  database:
    dsn: "@=env('DATABASE_URL')"
    table: "rejected_items"
  
  # OR Queue rejection
  queue:
    connection: "rabbitmq"
    queue: "rejected_items"
```

## Expression Language

The configuration supports dynamic expressions using the `@=` prefix:

### Environment Variables
```yaml
database_url: "@=env('DATABASE_URL')"
```

### Input Data Access
```yaml
field_value: "@=input['field_name']"
nested_value: "@=input['user']['email']"
```

### Conditional Logic
```yaml
status: "@=input['active'] ? 'enabled' : 'disabled'"
```

### Function Calls
```yaml
formatted_name: "@=upper(input['name'])"
joined_tags: "@=join(',', input['tags'])"
current_time: "@=now()"
```

### Array Operations
```yaml
first_item: "@=first(input['items'])"
item_count: "@=count(input['items'])"
filtered_items: "@=filter(input['items'], item => item['active'])"
```

## Validation Rules

### Required Fields
- `satellites[].label`: Every satellite must have a label
- Satellite type: One of `pipeline`, `workflow`, `api`, `hook`, or `action`
- Runtime: One of `docker`, `kubernetes`, or other configured runtimes

### Mutual Exclusions
- Only one satellite type per satellite
- Only one runtime type per satellite
- Only one extractor type per pipeline
- Only one loader type per pipeline

### Dependencies
- Pipelines require both extractor and loader
- Workflows require at least one job
- APIs require at least one endpoint
- Hooks require path and methods

## Best Practices

### Configuration Organization
```yaml
# Use clear, descriptive labels
satellites:
  - label: "User Data Synchronization Pipeline"
    
    # Group related configuration
    docker:
      # Docker configuration
    
    pipeline:
      # Pipeline configuration
```

### Environment Variables
```yaml
# Use environment variables for sensitive data
database:
  dsn: "@=env('DATABASE_URL')"
  username: "@=env('DB_USERNAME')"
  password: "@=env('DB_PASSWORD')"
```

### Expression Language
```yaml
# Use expressions for dynamic configuration
output_file: "@=sprintf('output_%s.csv', date('Y-m-d'))"
batch_size: "@=env('BATCH_SIZE', 1000)"
```

### Error Handling
```yaml
# Always configure rejection handling
rejection:
  file_path: "rejected_items.csv"

# Include logging for debugging
logger:
  type: "file"
  path: "pipeline.log"
  level: "info"
```

This configuration schema provides the foundation for building powerful, flexible data processing pipelines with the Gyroscops Compiler.