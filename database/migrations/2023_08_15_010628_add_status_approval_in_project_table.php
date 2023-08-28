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
        Schema::table('projects', function (Blueprint $table) {
            $table->string('mechanical_approval_status');
            $table->string('civil_approval_status');
            $table->string('electrical_approval_status');
            $table->string('instrument_approval_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('mechanical_approval_status');
            $table->dropColumn('civil_approval_status');
            $table->dropColumn('electrical_approval_status');
            $table->dropColumn('instrument_approval_status');
        });
    }
};
