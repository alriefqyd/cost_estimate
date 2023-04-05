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
        Schema::table('work_elements', function (Blueprint $table) {
            $table->foreignId('work_breakdown_structures_discipline_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('work_elements', function (Blueprint $table) {
            $table->removeColumn('work_breakdown_structures_discipline_id');
        });
    }
};
