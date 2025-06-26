# PHP-ETL Prestashop plugin

The Prestashop plugin aims at connecting a Prestashop API to ETL pipelines.

> Read [the complete and up-to-date documentation](https://php-etl.github.io/documentation/connectivity/prestashop) online.

## Installation
```shell
composer require php-etl/prestashop-plugin
```

## Quick start
### Building an extractor

```yaml
prestashop:
  client:
    url: 'https://prestashop.example.com'
    api_key: 'abc1234'
  extractor:
    type: 'products'
    method: 'all'
    options:
      columns:
        - 'id'
        - 'product_type'
        - 'price'
      filter:
        id: '[1,10]'
      sorters:
        id: ASC
      id_shop: 1
      id_group_shop: 1
      languages:
        from: 1
        to: 3
      price:
        my_price:
          product_attribute: 25
```

### Building a loader
```yaml
prestashop:
  client:
    url: 'https://prestashop.example.com'
    api_key: 'abc1234'
  loader:
    type: 'products'
    method: 'create'
    options: 
      id_shop: 1
      id_group_shop: 1
```

> Read the [online documentation](https://php-etl.github.io/documentation/connectivity/prestashop) for more details.
