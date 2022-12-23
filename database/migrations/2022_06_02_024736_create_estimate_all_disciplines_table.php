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
        Schema::create('estimate_all_disciplines', function (Blueprint $table) {
            $table->id();
            $table->integer('project_id');
            $table->string('work_scope');
            $table->string('title');
            $table->integer('work_type_id');
            $table->integer('work_item_id');
            $table->decimal('volume', 5, 4);
            $table->decimal('labor_cost_total_rate',9,2);
            $table->decimal('labor_unit_rate',9,2);
            $table->decimal('tool_unit_rate',9,2);
            $table->decimal('tool_unit_rate_total',9,2);
            $table->decimal('material_unit_rate',9,2);
            $table->decimal('material_unit_rate_total',9,2);
            $table->decimal('total_work_cost',9,2);
            $table->decimal('contigency',9,2);
            $table->string('status');
            $table->text('remark');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estimate_all_disciplines');
    }
};
