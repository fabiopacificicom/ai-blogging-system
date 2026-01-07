# Autonomous Blogging System for Laravel

This package is designed to add an AI-powered blogging system to laravel applications.
Test the package on a fresh laravel install.

## Installation

```bash

composer require pacificdev/autonomous-blogging-system
php artisan blogai:install
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

## LinkedIn Config (package default)

This package ships a publishable `linkedin` configuration with sensible defaults for autonomous sharing.

To publish the config into your application run:

```bash
php artisan vendor:publish --tag=pacificdev:ai-blog-config
```

The published file will be available at `config/linkedin.php`. Example `share_models` mapping you can set in your app config:

```php
return [
    'share_models' => [
        'post' => \PacificDev\BlogAi\Models\Post::class,
        'course' => \App\Models\Course::class,
    ],
    // other options: 'share_cooldown_days', 'content_rotation_strategy', 'max_shares_per_day'
];
```

This allows projects to override which models the package scheduler will consider for LinkedIn sharing.

### Useful ENV keys

The package uses OpenAI to generate share text; add these to your `.env`:

```env
OPENAI_API_KEY=your_api_key_here
LINKEDIN_PROMOTIONAL_TEMPLATE="Create a compelling LinkedIn post (max 300 chars) to promote this item:\nTitle: {title}\nSummary: {summary}"
```

`LINKEDIN_PROMOTIONAL_TEMPLATE` can be used by the scheduler to seed the AI prompt; the package exposes `config('linkedin.promotional_post_template')` for this purpose.


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
               'resources/js/vendor/pacificdev/blog-ai/app.js', // 👈 Add blog js
                'resources/scss/vendor/pacificdev/blog-ai/app.scss',  // 👈 Add blog scss
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

Run the package locally, place it in a folder and link it in the repositoris array in your composer.json file

```json
 "repositories": [
        {
            "type": "path",
            "url": "U:\\home\\pacificdev\\Projects\\packages\\ai-blogging-system"
        }
    ],

```
