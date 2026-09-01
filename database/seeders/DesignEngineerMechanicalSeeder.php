<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * One-off provisioning seeder: creates 9 "Design Engineer Mechanical" accounts.
 *
 * Access granted:
 *  - View-only: Material, Man Power, Work Item (read role, no create/update/delete)
 *  - View Cost Estimate Assignee (see projects where they are the assigned engineer)
 *  - Create Cost Estimate (cost_estimate/create)
 *  - Full access to develop their own cost estimate (read/create/update on
 *    the Estimate Discipline feature). Note: this grants access to the
 *    Estimate Discipline *feature* generally — per-project scoping to their
 *    own Mechanical discipline is enforced elsewhere in the app via
 *    profiles.position ('design_mechanical_engineer' -> discipline
 *    'mechanical') plus each Project's design_engineer_mechanical column.
 *    A Project Manager/Admin still needs to assign each of these engineers
 *    to their specific projects (via the project edit screen) before they
 *    can actually develop an estimate on that project.
 *
 * SECURITY NOTE: this file embeds real plaintext passwords for the seed run.
 * Passwords are bcrypt-hashed (Hash::make) before being written to the DB —
 * they are never stored in plaintext — but the plaintext still lives in this
 * source file. Do NOT commit this file to version control as-is: either
 * strip the passwords / delete this file after running it once, or move the
 * `people` list to an untracked file before committing.
 *
 * Run:  php artisan db:seed --class=DesignEngineerMechanicalSeeder
 */
class DesignEngineerMechanicalSeeder extends Seeder
{
    /**
     * Stamped as created_by/updated_by on the role assignments (user_role
     * table requires these). Auto-detected below as the first user whose
     * profile position is 'administrator'; hardcode a specific user id here
     * instead if auto-detection doesn't find the right account.
     */
    private ?int $adminUserId = null;

    private array $people = [
        ['user_name' => 'YJZ8694',  'full_name' => 'Narda',               'password' => 'Sorowako@2026'],
        ['user_name' => 'P4000016', 'full_name' => 'Abadi Setiawan',      'password' => 'Sorowako@2027'],
        ['user_name' => 'NLH5998',  'full_name' => 'Boas',                'password' => 'Sorowako@2026'],
        ['user_name' => 'MXJ7906',  'full_name' => 'Gresa F. Alberto',    'password' => 'Sorowako@2026'],
        ['user_name' => 'LCH7464',  'full_name' => 'Rasyidah Fadhlillah', 'password' => 'Sorowako@2026'],
        ['user_name' => 'VNR6088',  'full_name' => 'Robby Christian',     'password' => 'Sorowako@2027'],
        ['user_name' => 'WVR5629',  'full_name' => 'Evendy Jumaha',       'password' => 'Sorowako@2028'],
        ['user_name' => 'TBT7526',  'full_name' => 'Jusrianto',           'password' => 'Sorowako@2029'],
        ['user_name' => 'NUH6660',  'full_name' => 'Aqila Dwi Salsabila', 'password' => 'Sorowako@2030'],
    ];

    public function run(): void
    {
        $this->adminUserId = $this->adminUserId ?? optional(
            User::whereHas('profiles', fn ($q) => $q->where('position', 'administrator'))->first()
        )->id;

        if (!$this->adminUserId) {
            $this->command?->error('No administrator profile found — set $adminUserId manually at the top of this seeder before running.');
            return;
        }

        $roleIds = $this->ensureRoles();

        foreach ($this->people as $person) {
            try {
                // One transaction per person: a mid-person failure never leaves an
                // orphan `users` row behind (which is exactly what a previous run of
                // the un-wrapped version did).
                DB::transaction(function () use ($person, $roleIds) {
                    $this->provision($person, $roleIds);
                });
                $this->command?->info("Provisioned {$person['full_name']} ({$person['user_name']})");
            } catch (\Throwable $e) {
                // Keep going with the rest of the list instead of aborting the whole run.
                $this->command?->error("Skipped {$person['user_name']}: {$e->getMessage()}");
            }
        }
    }

    /**
     * Idempotent provision of one engineer. Reconciles against whatever a prior
     * (partial) run left behind:
     *   - `users.user_name` is NOT unique (only `users.email` is), so we cannot
     *     rely on firstOrCreate(['user_name' => ...]); match on the placeholder
     *     email / an existing profile instead.
     *   - An existing `profiles` row (unique on `email`) is re-linked to the user
     *     rather than re-inserted.
     */
    private function provision(array $person, array $roleIds): void
    {
        // TODO: replace with the person's real corporate email if available —
        // login uses user_name (see config/fortify.php), not email, but the
        // `email` column is required and unique on both users and profiles.
        $email = strtolower($person['user_name']) . '@placeholder.local';

        $profile = Profile::where('email', $email)->first();

        $user = $profile ? User::find($profile->user_id) : null;
        $user = $user ?: User::where('user_name', $person['user_name'])
            ->where('email', $email)
            ->first();

        if (!$user) {
            $user = User::firstOrCreate(
                ['email' => $email],
                ['user_name' => $person['user_name'], 'password' => Hash::make($person['password'])]
            );
        }

        $profile = $profile ?: Profile::firstOrNew(['user_id' => $user->id]);
        $profile->user_id   = $user->id;
        $profile->full_name = $profile->full_name ?: $person['full_name'];
        $profile->position  = 'design_mechanical_engineer';
        $profile->email     = $profile->email ?: $email;
        $profile->save();

        // Keep the reverse pointer some parts of the app read (users.profile_id).
        if ((int) $user->profile_id !== (int) $profile->id) {
            $user->profile_id = $profile->id;
            $user->save();
        }

        $existingRoleIds = $user->roles()->pluck('roles.id')->all();
        $toAttach = array_diff($roleIds, $existingRoleIds);

        if (!empty($toAttach)) {
            $pivotData = [];
            foreach ($toAttach as $roleId) {
                $pivotData[$roleId] = [
                    'created_by' => $this->adminUserId,
                    'updated_by' => $this->adminUserId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            $user->roles()->attach($pivotData);
        }
    }

    /**
     * Creates (or reuses) the Role rows needed, then returns their ids.
     * Matches the existing convention in RoleSeeder — keyed on action+feature
     * so re-running this seeder never creates duplicate role rows.
     */
    private function ensureRoles(): array
    {
        $defs = [
            ['action' => 'read',   'feature' => 'material',            'name' => 'View Material'],
            ['action' => 'read',   'feature' => 'man_power',           'name' => 'View Man Power'],
            ['action' => 'read',   'feature' => 'estimate_discipline', 'name' => 'View Estimate Discipline'],
            ['action' => 'create', 'feature' => 'estimate_discipline', 'name' => 'Create Estimate Discipline'],
            ['action' => 'update', 'feature' => 'estimate_discipline', 'name' => 'Update Estimate Discipline'],

            // View cost estimates assigned to them (ProjectPolicy::viewAny/view and
            // User::isAssigneeCostEstimateRole() both match on this exact name —
            // Role::ACTION_COST_ESTIMATE['read_assignee'] — so do NOT change it).
            ['action' => 'read_assignee', 'feature' => 'cost_estimate', 'name' => 'View Cost Estimate Assignee'],
            // View the Work Item list/detail (WorkItemPolicy, feature+action match).
            ['action' => 'read',          'feature' => 'work_item',     'name' => 'View Work Item'],
            // Create cost estimates (ProjectPolicy::create, feature+action match).
            ['action' => 'create',        'feature' => 'cost_estimate', 'name' => 'Create Cost Estimate'],
        ];

        $ids = [];
        foreach ($defs as $def) {
            $role = Role::firstOrCreate(
                ['action' => $def['action'], 'feature' => $def['feature']],
                ['name' => $def['name']]
            );

            // Backfill the name if an earlier run / another seeder created this
            // action+feature row without one (Role::$fillable excludes 'name',
            // but seeders run unguarded so this write still applies).
            if (blank($role->name) && !blank($def['name'])) {
                $role->name = $def['name'];
                $role->save();
            }

            $ids[] = $role->id;
        }

        return $ids;
    }
}
