# Autonomous Blogging System for Laravel

This package is designed to add an AI-powered blogging system to laravel applications.
Test the package on a fresh laravel install.

## Installation

```bash

composer require pacificdev/autonomous-blogging-system

```

Run the migrations

```bash
php artisan migrate

```

## Publish the package files

```bash

php artisan vendor:publish --tag=pacificdev:ai-blog-assets
php artisan vendor:publish --tag=pacificdev:ai-blog-config

```

By default the package uses bootstrap, therefore you need to publish the boostrap pagination

```bash
php artisan vendor:publish --tag=laravel-pagination 
```

## Install dependencies and update vite config

run npm install

```bash
npm i
```

Update the vite.config.js file

```js
import path from 'path';

export default defineConfig({
   plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/vendor/pacificdev/blog-ai/admin.js', // 👈 Add blog js
                'resources/scss/vendor/pacificdev/blog-ai/admin.scss',  // 👈 Add blog scss
            ],
            refresh: true,
        }),
    ],
    // 👇 Check these tree aliases are present in your config file, if not
    // add them as you see below.
    resolve: {
        alias: {
            '~icons': path.resolve(__dirname, 'node_modules/bootstrap-icons/font'),
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            '~resources': '/resources/'
        }
    }
});


```

## Update your .env file

Set the .env file
The package is configured to make posts searchable therefore you need to add the scout driver environment variable.

```env
OPENAI_API_KEY=your_api_key_here
SCOUT_DRIVER=database

```

## Customise views and components

You can also publish views, livewire and blade components

```bash

php artisan vendor:publish --tag=pacificdev:ai-blog-views
php artisan vendor:publish --tag=pacificdev:ai-blog-components
php artisan vendor:publish --tag=pacificdev:ai-blog-livewire-classes

```

**Laravel 11**
Add the super admin middleware inside bootstrap/app.php file
or remove the middleware form the routes if not required.

```php
use PacificDev\BlogAi\Http\Middleware\Blog\SuperAdmin;

->withMiddleware(function (Middleware $middleware) {
        # add this inside the callback 👇
        $middleware->append(SuperAdmin::class);
    })

```
