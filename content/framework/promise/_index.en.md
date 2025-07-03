---
title: "Promise"
date: 2020-07-12T15:21:02+02:00
draft: false
type: "dev_guide"
icon: "ti-timer"
description: "Promises for your asynchronous workloads"
---

{{< feature-state for_mw_version="0.1" state="alpha" >}}

### Definition of a Promise
A promise represents a single result of an asynchronous operation.

Two operations are called asynchronous when they are independent, i.e. 
when the second operation does not need to wait for the first to finish before starting.

This object allows you to synchronise two (or more) actions that take place in time but not at the same speed.

### Callbacks
The `then` method records reminders to receive the possible value of a promise, and the `failure` method represents the reason why it cannot be kept.
```php
<?php 

use Kiboko\Component\Promise\Promise;

$promise = new Promise();
$promise
   ->then(function ($value) {
        echo "Hello, ".$value;
   })
   ->failure(function (\Exception $exception) {
        echo 'Oups, error : '.$exception;
   })
;
```
### Resolving a promise
Promises are fulfilled using the resolve($value) method.

```php
<?php

$promise->resolve('world');
// Outputs 'Hello, world'
```

### Promise failure
Promises are failed using the fail($exception) method.

```php
<?php

$promise->fail(new Exception('Something went wrong'));
// Outputs 'Oups, error : Exception: Something went wrong'
```
