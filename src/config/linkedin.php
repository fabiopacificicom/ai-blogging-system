<?php

/*
|--------------------------------------------------------------------------
| LinkedIn Share Configuration (Package Default)
|--------------------------------------------------------------------------
|
| This file provides a sensible default configuration for autonomous
| LinkedIn sharing when the package is installed. Applications can publish
| this config and override values in their own `config/linkedin.php`.
|
*/

return [
    'share_cooldown_days' => env('LINKEDIN_SHARE_COOLDOWN_DAYS', 30),
    'content_rotation_strategy' => env('LINKEDIN_ROTATION_STRATEGY', 'oldest_unshared'), // oldest_unshared|random|performance
    'max_shares_per_day' => env('LINKEDIN_MAX_SHARES_PER_DAY', 3),
    'promotional_post_template' => env('LINKEDIN_PROMOTIONAL_TEMPLATE', 'Use AI to generate engaging promotional text'),
    // Default mapping of alias => model class used by the scheduler to select shareable content.
    // Consumers can publish this config and modify the mapping per-project. Example:
    // ['post' => \PacificDev\BlogAi\Models\Post::class, 'course' => \App\Models\Course::class]
    'share_models' => [
        'post' => \PacificDev\BlogAi\Models\Post::class,
    ],
];
