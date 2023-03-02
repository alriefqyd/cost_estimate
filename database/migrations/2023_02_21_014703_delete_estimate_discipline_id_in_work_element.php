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
        Schema::table('work_elements', function (Blueprint $table) {
            $table->dropColumn('estimate_disciplines_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('work_elements', function (Blueprint $table) {
            $table->foreignId('estimate_disciplines_id');
        });
    }
};
