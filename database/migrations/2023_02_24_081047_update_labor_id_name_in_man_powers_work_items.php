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
        Schema::table('man_powers_work_items', function (Blueprint $table) {
            $table->renameColumn('labor_id','man_power_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('man_powers_work_items', function (Blueprint $table) {
            $table->renameColumn('man_power_id','labor_id');
        });
    }
};
