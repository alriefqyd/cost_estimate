<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $newRoles = [
            [
                'action'  => 'read_it',
                'feature' => 'cost_estimate',
                'name'    => 'View All IT Cost Estimate',
            ],
            [
                'action'  => 'read_architect',
                'feature' => 'cost_estimate',
                'name'    => 'View All Architect Cost Estimate',
            ],
        ];

        foreach ($newRoles as $role) {
            DB::table('roles')->updateOrInsert(
                ['action' => $role['action'], 'feature' => $role['feature']],
                array_merge($role, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
