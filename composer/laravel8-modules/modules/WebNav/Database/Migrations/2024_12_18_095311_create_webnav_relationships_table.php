<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Illuminate\Database\Migrations\Migration;

class CreateWebNavRelationshipsTable extends Migration
{
    protected $prefix = "";
    protected $tableName = "relationships";
    protected $status = "protected";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!$tableName = $this->getTableName())
            return;
        // alter table `relationships` add `webnav_id` int null comment 'WebNav';
        Schema::table($tableName, function (Blueprint $table) use ($tableName) {
            $table->integer('webnav_id')->nullable()->comment("WebNav");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!$tableName = $this->getTableName())
            return;
        Schema::dropIfExists($tableName);
    }
}
