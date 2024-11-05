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
        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('design_engineer_architect')->nullable();
            $table->foreignId('architect_approver')->nullable();
            $table->string('architect_approval_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('design_engineer_architect');
            $table->dropColumn('architect_approver');
            $table->dropColumn('architect_approval_status');
        });
    }
};
