# Autonomous Blogging System for Laravel

This package is designed to add an AI-powered blogging system to laravel applications.
Test the package on a fresh laravel install.

## Installation

```bash

composer require pacificdev/ai-cms

```

## Publish the package files

```bash

php artisan vendor:publish --tag=pacificdev:blog-ai-assets
```

Run the migrations

```bash

php artisan migrate

```

**Laravel 11**
Add the super admin middleware inside bootstrap/app.php file
or remove the middleware form the routes in not required.

```php
use PacificDev\BlogAi\Http\Middleware\Blog\SuperAdmin;

->withMiddleware(function (Middleware $middleware) {
        # add this inside the callback 👇
        $middleware->append(SuperAdmin::class);
    })

```
