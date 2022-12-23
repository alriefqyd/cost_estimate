<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        schema::rename('project_info','projects');
        schema::rename('discipline_work_type','discipline_work_types');
        schema::rename('equipment_tools_category','equipment_tools_categorys');
        schema::rename('materials_category','materials_categorys');
        schema::rename('work_item_type','work_item_types');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
