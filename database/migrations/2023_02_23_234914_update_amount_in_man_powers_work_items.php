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
            $table->decimal('amount',50,2)->change();
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
            $table->decimal('amount',9,2)->change();
        });
    }
};
