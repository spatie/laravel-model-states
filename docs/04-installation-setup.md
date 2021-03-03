---
title: Installation & setup
weight: 4
---

laravel-model-states can install the package via composer:

```bash
composer require spatie/laravel-model-states
```

## Publishing the config file

Publishing the config file is optional:

```bash
php artisan vendor:publish --provider="Spatie\ModelStates\ModelStatesServiceProvider" --tag="laravel-model-states-config"
```

This is the default content of the config file:

```php
return [

    /*
     * The fully qualified class name of the default transition.
     */
    'default_transition' => Spatie\ModelStates\DefaultTransition::class,

];
```
