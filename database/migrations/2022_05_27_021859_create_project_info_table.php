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
        Schema::create('project_info', function (Blueprint $table) {
            $table->id();
            $table->string('project_no');
            $table->longText('project_title');
            $table->longText('sub_project_title');
            $table->string('project_sponsor');
            $table->string('project_manager');
            $table->string('project_engineer');
            $table->string('design_engineer_mechanical');
            $table->string('design_engineer_civil');
            $table->string('design_engineer_electrical');
            $table->string('design_engineer_instrument');
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
        Schema::dropIfExists('project_info');
    }
};
