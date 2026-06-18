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
        Schema::table('estimate_all_disciplines', function (Blueprint $table) {
            // When the owning user is deleted, release ownership so the row becomes editable by all.
            $table->foreign('scope_owner_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('estimate_all_disciplines', function (Blueprint $table) {
            $table->dropForeign(['scope_owner_id']);
        });
    }
};
