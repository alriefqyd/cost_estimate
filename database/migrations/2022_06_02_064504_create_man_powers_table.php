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
        Schema::create('man_powers', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('skill_level');
            $table->string('title');
            $table->decimal('basic_rate_month',20,2);
            $table->decimal('basic_rate_hour',20,2);
            $table->decimal('general_allowance',20,2);
            $table->decimal('bpjs',20,2);
            $table->decimal('bpjs_kesehatan',20,2);
            $table->decimal('thr',20,2);
            $table->decimal('public_holiday',20,2);
            $table->decimal('leave',20,2);
            $table->decimal('pesangon',20,2);
            $table->decimal('asuransi',20,2);
            $table->decimal('safety',20,2);
            $table->decimal('total_benefit_hourly',20,2);
            $table->decimal('overall_rate_hourly',20,2);
            $table->decimal('factor_hourly',20,2);
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
        Schema::dropIfExists('labors');
    }
};
