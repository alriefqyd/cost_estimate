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
        Schema::create('work_items', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->integer('work_item_type_id');
            $table->longText('description');
            $table->string('volume');
            $table->string('unit');
            $table->decimal('total',20,2)->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('reviewed_by')->nullable();
            $table->integer('labor_id')->nullable();
            $table->integer('material_id')->nullable();
            $table->integer('tools_equipment_id')->nullable();
            $table->decimal('amount_material',20,2)->nullable();
            $table->decimal('amount_tools_equipment',20,2)->nullable();
            $table->decimal('amount_labor',20,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_items');
    }
};
