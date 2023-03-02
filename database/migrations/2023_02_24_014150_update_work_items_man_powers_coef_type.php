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
            $table->string('labor_coefisient')->change();
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
            $table->decimal(9,2,'labor_coefisient')->change();
        });
    }
};
