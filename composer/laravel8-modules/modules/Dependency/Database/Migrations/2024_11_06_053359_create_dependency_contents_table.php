<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDependencyContentsTable extends Migration
{
    protected $table = 'dependency_contents';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->id('cid');

            $table->string('ico')->nullable();
            $table->string('slug')->nullable();
            $table->string('name')->nullable();
            $table->string('keywords')->nullable();
            $table->string('description')->nullable();
            $table->string('mainfile')->nullable();
            $table->longText('text')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->nullable();
            $table->integer('parent')->nullable()->default(0);
            $table->integer('count')->nullable()->default(0);
            $table->integer('order')->nullable()->default(0);
            $table->integer('user')->nullable()->default(0);
            $table->integer('template')->nullable()->default(0);

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
        Schema::dropIfExists($this->table);
    }
}
