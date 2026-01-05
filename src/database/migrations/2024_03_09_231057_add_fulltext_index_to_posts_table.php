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
        // Make sure to wrap the index creation in a raw statement
        // as Laravel's Schema builder does not support fulltext indexes natively
        DB::statement('ALTER TABLE posts ADD FULLTEXT fulltext_index(content)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // To drop the fulltext index, use a raw statement as well
        DB::statement('ALTER TABLE posts DROP INDEX fulltext_index');
    }
};
