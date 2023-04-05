<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkBreakdownStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = [
            ['title' => 'GENERAL','code'=>'general','level'=>'2','parent_id' => ''],
            ['title' => 'CIVIL / STRUCTURE WORK','code'=>'civil','level'=>'2','parent_id' => ''],
            ['title' => 'MECHANICAL','code'=>'mechanical','level'=>'2','parent_id' => ''],
            ['title' => 'ELECTRICAL','code'=>'electrical','level'=>'2','parent_id' => ''],
            ['title' => 'INSTRUMENT','code'=>'instrument','level'=>'2','parent_id' => ''],
            ['title' => 'DETAILED ENGINEERING','code'=>'detailed_engineering','level'=>'2','parent_id' => ''],
            ['title' => 'PROJECT MANAGEMENT','code'=>'project_management','level'=>'2','parent_id' => ''],
            ['title' => 'CONSTRUCTION MANAGEMENT','code'=>'construction_management','level'=>'2', 'parent_id' => ''],
            ['title' => 'COMMISSIONING','code'=>'commissioning','level'=>'2', 'parent_id' => ''],
            ['title' => 'TRAVEL AND ACCOMODATION','code'=>'travel_and_accomodation','level'=>'2', 'parent_id' => ''],
        ];




        DB::table('work_breakdown_structures')->insert(
            $data
        );
    }
}
