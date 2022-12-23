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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('category_id');
            $table->string('code');
            $table->integer('quantity')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('rate',15,2)->nullable();
            $table->text('ref_material_number')->nullable();
            $table->text('remark')->nullable();
            $table->text('tool_equipment_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('stock_code')->nullable();
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
        Schema::dropIfExists('materials');
    }
};
