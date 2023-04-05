<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    public const DESIGN_ENGINEER_LIST = [
        'civil' => 'Civil',
        'mechanical' => 'Mechanical',
        'electrical' => 'Electrical',
        'instrument' => 'Instrument'
    ];

    public const DISCIPLINE = [
        'general' => 'GENERAL',
        'civil' => 'CIVIL / STRUCTURE WORK',
        'mechanical' => 'MECHANICAL',
        'electrical' => 'ELECTRICAL',
        'instrument' => 'INSTRUMENT',
        'detailed_engineering' => 'DETAILED ENGINEERING',
        'project_management' => 'PROJECT MANAGEMENT',
        'construction_management' => 'CONSTRUCTION MANAGEMENT',
        'commissioning' => 'COMMISSIONING',
        'travel_and_accomodation' => 'TRAVEL AND ACCOMODATION',
        'contigency' => 'CONTIGENCY'
    ];

    public const WORK_ELEMENT_DISCIPLINE = [
        'insurance_bond_and_permit' => 'INSURANCE, BOND AND PERMIT',
        'security_and_safety' => 'SECURITY AND SAFETY',
        'contractor_facilities' => 'CONTRACTOR FACILITIES',
        'mobilization' => 'MOBILIZATION',
        'demobilization' => 'DEMOBILIZATION',
        'pti_engineering' => 'PTI ENGINEERING',
        'consulting_engineering' => 'CONSULTING ENGINEERING',
        'pti_project_management' => 'PTI PROJECT MANAGEMENT',
        'consultant_project_management' => 'CONSULTANT PROJECT MANAGEMENT',
        'site_supervision' => 'SITE SUPERVISION',
        'safety' => 'SAFETY',
        'pre_commissioning_all_disciplines' => 'PRE COMMISSIONING ALL DISCIPLINES',
        'final_commissioning_all_disciplines' => 'FINAL COMMISSIONING ALL DISCIPLINES',
        'general_vendor_technical_assistance' => 'GENERAL VENDOR TECHNICAL ASSISTANCE',
        'site_accommodation_vehicles_air_travel' => 'SITE ACCOMMODATION,VEHICLES,AIR TRAVEL',
        'off_site_accommodation_and_air_travel' => 'OFF SITE ACCOMMODATION AND AIR TRAVEL'
    ];
}
