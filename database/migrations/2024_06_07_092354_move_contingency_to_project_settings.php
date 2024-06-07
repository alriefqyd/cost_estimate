<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            DB::table('projects')->orderBy('id')->chunk(100, function ($projects) {
                foreach ($projects as $project) {
                    DB::table('project_settings')->insert([
                        'project_id' => $project->id,
                        'contingency' => $project->contingency,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('project_settings')->truncate();
    }
};
