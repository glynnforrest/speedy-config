# Speedy Config

[![Build Status](https://travis-ci.org/glynnforrest/speedy-config.svg?branch=master)](https://travis-ci.org/glynnforrest/speedy-config)

Load configuration from a variety of sources, process and validate it,
then cache the result for speedy loading the next time.

## Install

```bash
composer require glynnforrest/speedy-config
```

## Usage

Create a config builder with the loaders and processors to use:

```php

use SpeedyConfig\ConfigBuilder;
use SpeedyConfig\Loader\YamlLoader;
use SpeedyConfig\Loader\PhpLoader;
use SpeedyConfig\Processor\ReferenceProcessor;

$builder = new ConfigBuilder([new YamlLoader(), new PhpLoader()], new ReferenceProcessor());
```

Add resources to load:

```php
$builder->addResource('config.php')
    ->addResource('config.yml');
```

Then get the resolved configuration.

```php
$config = $builder->getConfig();

// instance of SpeedyConfig\Config
```
