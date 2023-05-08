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
            $table->integer('labour_factorial')->nullable();
            $table->integer('equipment_factorial')->nullable();
            $table->integer('material_factorial')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estimate_all_disciplines', function (Blueprint $table) {
            $table->removeColumn('labour_factorial');
            $table->removeColumn('equipment_factorial');
            $table->removeColumn('material_factorial');
        });
    }
};
