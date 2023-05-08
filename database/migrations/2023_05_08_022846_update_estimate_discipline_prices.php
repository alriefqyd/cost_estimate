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
            $table->decimal('labor_unit_rate',50,2)->change();
            $table->decimal('labor_cost_total_rate',50,2)->change();
            $table->decimal('tool_unit_rate',50,2)->change();
            $table->decimal('tool_unit_rate_total',50,2)->change();
            $table->decimal('material_unit_rate',50,2)->change();
            $table->decimal('material_unit_rate_total',50,2)->change();
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
            $table->decimal('labor_unit_rate',9,2)->change();
            $table->decimal('labor_cost_total_rate',9,2)->change();
            $table->decimal('tool_unit_rate',9,2)->change();
            $table->decimal('tool_unit_rate_total',9,2)->change();
            $table->decimal('material_unit_rate',9,2)->change();
            $table->decimal('material_unit_rate_total',9,2)->change();
        });
    }
};
