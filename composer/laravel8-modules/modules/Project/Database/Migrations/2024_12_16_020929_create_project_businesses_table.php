<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectBusinessesTable extends \App\Illuminate\Database\Migrations\Migration
{
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
        Schema::create('project_businesses', function (Blueprint $table) {
            $table->id();

            $table->timestamps();
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
        Schema::dropIfExists('project_businesses');
    }
}
