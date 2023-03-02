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
        Schema::create('work_items_equipment_tools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_item_id');
            $table->foreignId('equipment_tools_id');
            $table->string('unit')->nullable();
            $table->float('quantity',20,2)->nullable();
            $table->decimal('amount',50,2)->nullable();
            $table->decimal('unit_price',50,2)->nullable();
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
        Schema::dropIfExists('work_items_equipment_tools');
    }
};
