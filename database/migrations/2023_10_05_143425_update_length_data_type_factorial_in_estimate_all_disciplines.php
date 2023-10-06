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
            $table->decimal('equipment_factorial', 5, 2)->change();
            $table->decimal('material_factorial', 5, 2)->change();
            $table->decimal('labour_factorial', 5, 2)->change();
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
            //
        });
    }
};
