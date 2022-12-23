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
        Schema::table('project_info', function (Blueprint $table) {
            $table->foreignId('design_engineer_mechanical')->change()->nullable();
            $table->foreignId('design_engineer_civil')->change()->nullable();
            $table->foreignId('design_engineer_electrical')->change()->nullable();
            $table->foreignId('design_engineer_instrument')->change()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_info', function (Blueprint $table) {

        });
    }
};
