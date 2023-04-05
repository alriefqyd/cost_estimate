<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkBreakdownStructureWorkElementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $generalId = DB::table('work_breakdown_structures')->where('code','general')->first();
        $civilId = DB::table('work_breakdown_structures')->where('code','civil')->first();
        $mechanicalId = DB::table('work_breakdown_structures')->where('code','mechanical')->first();
        $electricalId = DB::table('work_breakdown_structures')->where('code','electrical')->first();
        $instrumentId = DB::table('work_breakdown_structures')->where('code','instrument')->first();
        $detailed_engineeringId = DB::table('work_breakdown_structures')->where('code','detailed_engineering')->first();
        $projectManagementId = DB::table('work_breakdown_structures')->where('code','project_management')->first();
        $constructionManagementId = DB::table('work_breakdown_structures')->where('code','construction_management')->first();
        $commissioningId = DB::table('work_breakdown_structures')->where('code','commissioning')->first();
        $travelAndAccomodationId = DB::table('work_breakdown_structures')->where('code','travel_and_accomodation')->first();
        $contigencyId = DB::table('work_breakdown_structures')->where('code','contigency')->first();

        $data2 = [
            ['title' => 'INSURANCE, BOND AND PERMIT','code'=>'insurance_bond_and_permit','level'=>'3', 'parent_id' => $generalId->id],
            ['title' => 'SECURITY AND SAFETY','code'=>'security_and_safety','level'=>'3', 'parent_id' => $generalId->id],
            ['title' => 'CONTRACTOR FACILITIES','code'=>'contractor_facilities','level'=>'3', 'parent_id' => $generalId->id],
            ['title' => 'MOBILIZATION','code'=>'mobilization','level'=>'3', 'parent_id' => $generalId->id],
            ['title' => 'DEMOBILIZATION','code'=>'demobilization','level'=>'3', 'parent_id' => $generalId->id],
            ['title' => 'PTI ENGINEERING, BOND AND PERMIT','code'=>'pti_engineering','level'=>'3', 'parent_id' => $detailed_engineeringId->id],
            ['title' => 'CONSULTING ENGINEERING, BOND AND PERMIT','code'=>'consulting_engineering','level'=>'3', 'parent_id' => $detailed_engineeringId->id],
            ['title' => 'PTI PROJECT MANAGEMENT, BOND AND PERMIT','code'=>'pti_project_management','level'=>'3', 'parent_id' => $projectManagementId->id],
            ['title' => 'CONSULTANT PROJECT MANAGEMENT','code'=>'consultant_project_management','level'=>'3', 'parent_id' => $projectManagementId->id],
            ['title' => 'SITE SUPERVISION','code'=>'site_supervision','level'=>'3', 'parent_id' => $constructionManagementId->id],
            ['title' => 'SAFETY','code'=>'safety','level'=>'3', 'parent_id' => $constructionManagementId->id],
            ['title' => 'PRE COMMISSIONING ALL DISCIPLINES','code'=>'pre_commissioning_all_disciplines','level'=>'3', 'parent_id' => $commissioningId->id],
            ['title' => 'FINAL COMMISSIONING ALL DISCIPLINES','code'=>'final_commissioning_all_disciplines','level'=>'3', 'parent_id' => $commissioningId->id],
            ['title' => 'GENERAL VENDOR TECHNICAL ASSISTANCE','code'=>'general_vendor_technical_assistance','level'=>'3', 'parent_id' => $commissioningId->id],
            ['title' => 'SITE ACCOMMODATION,VEHICLES,AIR TRAVEL','code'=>'site_accommodation_vehicles_air_travel','level'=>'3', 'parent_id' => $travelAndAccomodationId->id],
            ['title' => 'OFF SITE ACCOMMODATION AND AIR TRAVEL','code'=>'off_site_accommodation_and_air_travel','level'=>'3', 'parent_id' => $travelAndAccomodationId->id],
        ];

        DB::table('work_breakdown_structures')->insert(
            $data2
        );
    }
}
