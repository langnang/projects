<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminMetasTable extends \App\Illuminate\Database\Migrations\Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_metas', function (Blueprint $table) {
            $table->id('mid');

            $table->string('slug')->nullable();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->nullable();

            $table->integer('parent')->nullable()->default(0);
            $table->integer('count')->nullable()->default(0);
            $table->integer('order')->nullable()->default(0);

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
        Schema::dropIfExists('admin_metas');
    }
}
