<?php

namespace PacificDev\BlogAi\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PacificDev\BlogAi\Models\Post;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'active', 'slug'];


    /**
     * Get all of the posts for the Topic
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
