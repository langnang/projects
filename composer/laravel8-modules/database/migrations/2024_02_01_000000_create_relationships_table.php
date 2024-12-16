<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Support\Module;

class CreateRelationshipsTable extends \App\Illuminate\Database\Migrations\Migration
{
    protected $prefix = "";
    protected $tableName = "relationships";
    protected $status = "private";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!$tableName = $this->getTableName())
            return;
        // 基本关联表
        Schema::create($tableName, function (Blueprint $table) {

            $table->integer('mid')->nullable();
            $table->integer('cid')->nullable();
            $table->integer('lid')->nullable();

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
