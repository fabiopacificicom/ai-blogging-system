<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('posts')) {
            Schema::create('posts', function (Blueprint $table) {
                $table->id();
                $table->string('author', 10)->nullable();
                $table->string('title');
                $table->string('slug')->unique();
                $table->mediumText('summary')->nullable();
                $table->longText('content')->nullable();
                $table->string('featured_image')->nullable();
                $table->mediumText('cover_image')->nullable();
                $table->enum('status', ['public', 'draft'])->default('draft');
                $table->boolean('is_published')->default(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
