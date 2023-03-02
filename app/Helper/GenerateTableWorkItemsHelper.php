<?php

namespace App\Helper;

use Illuminate\Support\Facades\DB;

class GenerateTableWorkItemsHelper {
    public function generateWorkItemJoinList(){
        return DB::statement("
            create view view_work_items_list as
            (select
            wi.code as 'work_item_code',
            wi.description,
            wi.volume,
            wi.unit,
            mp.title,
            mpwi.labor_unit,
            mpwi.labor_coefisient,
            mp.overall_rate_hourly,
            (mp.overall_rate_hourly * mpwi.labor_coefisient) as 'rate'
            from man_powers_work_items mpwi
            join work_items wi on mpwi.work_item_id = wi.id
            join man_powers mp on mp.id = mpwi.labor_id
            );
        ");
    }

    public function generateWorkItemJoinSummary(){
        DB::statement("
        create view view_work_items_summary as
            (select
                wi.code as 'work_item_code',
                wi.description,
                wi.volume,
                wi.unit,
                mp.title,
                mpwi.labor_unit,
                mpwi.labor_coefisient,
                mp.overall_rate_hourly,
                (mp.overall_rate_hourly * mpwi.labor_coefisient) as 'rate',
                sum(mp.overall_rate_hourly * mpwi.labor_coefisient) as 'total'
            from man_powers_work_items mpwi
            join work_items wi on mpwi.work_item_id = wi.id
            join man_powers mp on mp.id = mpwi.labor_id
            group by wi.code
            );
        ");
    }
}
