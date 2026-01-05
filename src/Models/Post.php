<?php

namespace PacificDev\BlogAi\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PacificDev\BlogAi\Models\Topic;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Searchable;


class Post extends Model
{
    use HasFactory, Searchable;

    protected $guarded = [];

    #[SearchUsingFullText(['content'])]
    public function toSearchableArray()
    {
        // Customize the data array...
        return [
            'id' => $this->id,
            'title' => $this->content,
            'summary' => $this->content,
            'content' => $this->content,
            'created_at' => $this->created_at,
            // Include other relevant fields
        ];
    }

    // Method to retrieve full-text search columns
    public static function getSearchableColumns()
    {

        return ['content'];
    }



    public function coverImagePath()
    {
        return asset('storage' . $this->cover_image);
    }

    /**
     * Get the topic that owns the Topic
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }
}
