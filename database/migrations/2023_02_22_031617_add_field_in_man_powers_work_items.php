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
            $table->string('labor_unit')->nullable();
            $table->integer('labor_coefisient')->nullable();
            $table->decimal('amount',9,2)->nullable();
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
            $table->dropColumn('labor_unit');
            $table->dropColumn('labor_coefisient');
            $table->dropColumn('amount');
        });
    }
};
