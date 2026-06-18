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
            // NULL  = legacy / unowned row → editable by any discipline.
            // Set   = owned row → editable only by that user's discipline (work_scope).
            $table->unsignedBigInteger('scope_owner_id')->nullable()->after('work_scope');
            $table->foreign('scope_owner_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('estimate_all_disciplines', function (Blueprint $table) {
            $table->dropForeign(['scope_owner_id']);
            $table->dropColumn('scope_owner_id');
        });
    }
};
