<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Illuminate\Database\Migrations\Migration;

class CreateWebHuntFieldsTable extends Migration
{
    protected $prefix = "";
    protected $tableName = "webhunt_fields";
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
            $table->string('selector_type')->nullable()->comment('名称');
            $table->string('selector')->nullable()->comment('名称');
            $table->string("filter")->nullable()->comment("过滤");
            $table->boolean("required")->nullable()->default(false);
            $table->boolean("repeated")->nullable()->default(false);
            $table->string("source_type")->nullable()->comment("过滤");
            $table->string("attached_url")->nullable()->comment("过滤");

            $table->timestamps();
            $table->timestamp('release_at')->nullable()->comment('发布时间');
            $table->timestamp('deleted_at')->nullable()->comment('删除时间');
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
