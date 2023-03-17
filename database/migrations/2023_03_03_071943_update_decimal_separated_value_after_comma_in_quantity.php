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
        Schema::table('work_items_equipment_tools', function (Blueprint $table) {
            $table->decimal('quantity',50,9)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('work_items_equipment_tools', function (Blueprint $table) {
            $table->decimal('quantity',50,2)->change();
        });
    }
};
