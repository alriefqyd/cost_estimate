<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_review_notes', function (Blueprint $table) {
            $table->unsignedBigInteger('estimate_discipline_id')->nullable()->change();
            $table->decimal('position_x', 10, 2)->nullable()->after('mark_type');
            $table->decimal('position_y', 10, 2)->nullable()->after('position_x');
        });
    }

    public function down(): void
    {
        Schema::table('project_review_notes', function (Blueprint $table) {
            $table->dropColumn(['position_x', 'position_y']);
            $table->unsignedBigInteger('estimate_discipline_id')->nullable(false)->change();
        });
    }
};
