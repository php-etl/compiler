Shopify Data Flows
===

[![Quality (PHPStan lvl 4)](https://github.com/php-etl/shopify-plugin/actions/workflows/quality.yaml/badge.svg)](https://github.com/php-etl/shopify-plugin/actions/workflows/quality.yaml)
[![PHPUnit](https://github.com/php-etl/shopify-plugin/actions/workflows/phpunit.yaml/badge.svg)](https://github.com/php-etl/shopify-plugin/actions/workflows/phpunit.yaml)
[![Infection](https://github.com/php-etl/shopify-plugin/actions/workflows/infection.yaml/badge.svg)](https://github.com/php-etl/shopify-plugin/actions/workflows/infection.yaml)
[![PHPStan level 5](https://github.com/php-etl/shopify-plugin/actions/workflows/phpstan-5.yaml/badge.svg)](https://github.com/php-etl/shopify-plugin/actions/workflows/phpstan-5.yaml)
[![PHPStan level 6](https://github.com/php-etl/shopify-plugin/actions/workflows/phpstan-6.yaml/badge.svg)](https://github.com/php-etl/shopify-plugin/actions/workflows/phpstan-6.yaml)
[![PHPStan level 7](https://github.com/php-etl/shopify-plugin/actions/workflows/phpstan-7.yaml/badge.svg)](https://github.com/php-etl/shopify-plugin/actions/workflows/phpstan-7.yaml)
[![PHPStan level 8](https://github.com/php-etl/shopify-plugin/actions/workflows/phpstan-8.yaml/badge.svg)](https://github.com/php-etl/shopify-plugin/actions/workflows/phpstan-8.yaml)
![PHP](https://img.shields.io/packagist/php-v/php-etl/shopify-plugin)

Goal
---

This package aims at integrating Shopify's REST API into the
[Pipeline](https://github.com/php-etl/pipeline) stack. This integration is
compatible with the [Shopify API](https://shopify.dev/docs/api)

Principles
---

The tools in this library will produce executable PHP sources, using an intermediate _Abstract Syntax Tree_ from
[nikic/php-parser](https://github.com/nikic/PHP-Parser). This intermediate format helps you combine 
the code produced by this library with other packages from [Middleware](https://github.com/php-etl).

Configuration format
---

### Building an extractor

```yaml
shopify:
  extractor:
    type: products
    method: all
    search:
      - { field: status, operator: '=', value: 'active' }
      - { field: product_type, operator: '=', value: 'Electronics' }
      - { field: vendor, operator: '=', value: 'Apple' }
      - { field: created_at_min, operator: '>=', value: '2023-01-01T00:00:00Z' }
  client:
    api_url: 'https://your-shop.myshopify.com'
    client_id: 'your_api_key'
    secret: 'your_api_secret'
    username: 'your_username'
    password: 'your_password'
```

### Building a loader

```yaml
shopify:
  loader:
    type: products
    method: upsert
  client:
    api_url: 'https://your-shop.myshopify.com'
    client_id: 'your_api_key'
    secret: 'your_api_secret'
    token: 'your_access_token'
    refresh_token: 'your_refresh_token'
```

### Building a lookup

```yaml
shopify:
  lookup:
    type: customers
    method: get
    search:
      - { field: email, operator: '=', value: '@=input["email"]' }
  client:
    api_url: 'https://your-shop.myshopify.com'
    client_id: 'your_api_key'
    secret: 'your_api_secret'
    username: 'your_username'
    password: 'your_password'
```

Usage
---

This library will build for you either an extractor, loader, or lookup, compatible with the Shopify REST API.

You can use the following PHP script to test and print the result of your configuration.

```php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Kiboko\Plugin\Shopify;
use PhpParser\Node;
use PhpParser\PrettyPrinter;
use Symfony\Component\Console;
use Symfony\Component\Yaml;

$input = new Console\Input\ArgvInput($argv);
$output = new Console\Output\ConsoleOutput();

class DefaultCommand extends Console\Command\Command
{
    protected static $defaultName = 'test';

    protected function configure()
    {
        $this->addArgument('file', Console\Input\InputArgument::REQUIRED);
    }

    protected function execute(Console\Input\InputInterface $input, Console\Output\OutputInterface $output)
    {
        $factory = new Shopify\Service();

        $style = new Console\Style\SymfonyStyle(
            $input,
            $output,
        );

        $config = Yaml\Yaml::parse(input: file_get_contents($input->getArgument('file')));

        $style->section('Validation');
        $style->writeln($factory->validate($config) ? '<info>ok</info>' : '<error>failed</error>');
        $style->section('Normalized Config');
        $style->writeln(\json_encode($config = $factory->normalize($config), JSON_PRETTY_PRINT));
        $style->section('Generated code');
        $style->writeln((new PrettyPrinter\Standard())->prettyPrintFile([
            new Node\Stmt\Return_($factory->compile($config)->getNode()),
        ]));

        return 0;
    }
}

(new Console\Application())
    ->add(new DefaultCommand())
    ->run($input, $output)
;
```

See also
---

* [php-etl/pipeline](https://github.com/php-etl/pipeline)
* [php-etl/fast-map](https://github.com/php-etl/fast-map)
* [Shopify REST API Documentation](https://shopify.dev/docs/api/rest)
* [Shopify API PHP Client](https://github.com/Shopify/shopify-api-php)
