<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_breakdown_structures', function (Blueprint $table) {
            if (!Schema::hasColumn('work_breakdown_structures', 'sort_order')) {
                $table->unsignedInteger('sort_order')->default(0)->after('parent_id');
            }
        });

        // Initialise sort_order from current id order so existing rows are stable
        DB::statement('SET @row := 0');
        DB::statement('UPDATE work_breakdown_structures SET sort_order = (@row := @row + 1) ORDER BY parent_id, id');
    }

    public function down(): void
    {
        Schema::table('work_breakdown_structures', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
