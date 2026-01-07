<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Skip fulltext index on SQLite (not supported)
        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            // Make sure to wrap the index creation in a raw statement
            // as Laravel's Schema builder does not support fulltext indexes natively
            DB::statement('ALTER TABLE posts ADD FULLTEXT fulltext_index(content)');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            // To drop the fulltext index, use a raw statement as well
            DB::statement('ALTER TABLE posts DROP INDEX fulltext_index');
        }
    }
};
