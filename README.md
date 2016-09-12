# News Package

## Install

Via Composer

``` bash
"andregoncalvesdev/laravelnews": "0.1.*"
```

## Usage
Then add the service provider in config/app.php:

```
AndreGoncalvesDev\LaravelNews\LaravelNewsServiceProvider::class,
```

``` php
php artisan vendor:publish
php artisan migrate
```
