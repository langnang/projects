<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Illuminate\Database\Migrations\Migration;

class CreateWebhuntGroupsTable extends Migration
{
    protected $prefix = "";
    protected $tableName = "webhunt_groups";
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
        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->integer("webhunt");

            $table->string('name')->nullable()->comment('名称');
            $table->string('additional_url')->nullable()->comment('附加地址');

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
        Schema::dropIfExists($tableName);
    }
}
