<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Support\Module;

class CreateRelationshipsTable extends Migration
{
    public $prefix = '';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 基本关联表
        Schema::create($this->prefix . '_relationships', function (Blueprint $table) {

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
        Schema::dropIfExists($this->prefix . '_relationships');
    }
}
