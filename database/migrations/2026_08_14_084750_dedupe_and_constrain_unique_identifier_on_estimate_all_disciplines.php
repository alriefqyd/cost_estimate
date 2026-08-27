<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    private string $indexName = 'estimate_all_disciplines_project_uid_unique';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Already soft-deleted rows are never looked up by unique_identifier again
        // (nothing in the app queries this table withTrashed), so give each one its
        // own private uid first. This clears out legacy blank/duplicate identifiers
        // sitting among soft-deleted rows, which would otherwise block the unique
        // index below — MySQL's unique index does not know about deleted_at.
        DB::table('estimate_all_disciplines')
            ->whereNotNull('deleted_at')
            ->get(['id'])
            ->each(function ($row) {
                DB::table('estimate_all_disciplines')
                    ->where('id', $row->id)
                    ->update(['unique_identifier' => (string) Str::uuid()]);
            });

        // Backfill active rows that never got a client-generated uid so they don't
        // collide with each other once uniqueness is enforced below.
        DB::table('estimate_all_disciplines')
            ->whereNull('deleted_at')
            ->where(function ($q) {
                // MySQL's TRIM() only strips plain spaces, not \n/\t/\r, so match
                // any all-whitespace value (or empty string) via regex instead.
                $q->whereNull('unique_identifier')
                    ->orWhereRaw("unique_identifier REGEXP '^[[:space:]]*$'");
            })
            ->get(['id'])
            ->each(function ($row) {
                DB::table('estimate_all_disciplines')
                    ->where('id', $row->id)
                    ->update(['unique_identifier' => (string) Str::uuid()]);
            });

        // Remove duplicate ACTIVE rows sharing the same (project_id, unique_identifier)
        // — these are the visible duplicates caused by the autosave race. Keep the
        // most recently updated one; soft-delete the rest and re-key them so they
        // don't reintroduce a collision against the row we kept.
        DB::table('estimate_all_disciplines')
            ->select('project_id', 'unique_identifier')
            ->whereNull('deleted_at')
            ->groupBy('project_id', 'unique_identifier')
            ->havingRaw('COUNT(*) > 1')
            ->get()
            ->each(function ($group) {
                $idsToRemove = DB::table('estimate_all_disciplines')
                    ->where('project_id', $group->project_id)
                    ->where('unique_identifier', $group->unique_identifier)
                    ->whereNull('deleted_at')
                    ->orderByDesc('updated_at')
                    ->orderByDesc('id')
                    ->pluck('id')
                    ->slice(1);

                foreach ($idsToRemove as $id) {
                    DB::table('estimate_all_disciplines')
                        ->where('id', $id)
                        ->update([
                            'deleted_at' => now(),
                            'unique_identifier' => (string) Str::uuid(),
                        ]);
                }
            });

        Schema::table('estimate_all_disciplines', function (Blueprint $table) {
            $table->unique(['project_id', 'unique_identifier'], $this->indexName);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estimate_all_disciplines', function (Blueprint $table) {
            $table->dropUnique($this->indexName);
        });
    }
};
