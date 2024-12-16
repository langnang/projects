<?php

namespace App\Illuminate\Database\Migrations;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

abstract class Migration extends \Illuminate\Database\Migrations\Migration
{
    /**
     * Summary of prefix
     * @var string
     */
    protected $prefix = "";
    /**
     * Summary of type
     * public
     * protected
     * private
     * @var string
     */
    protected $status = "private";
    protected $tableName = "";
    protected $tableColumns = [];
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!$tableName = $this->getTableName())
            return;
    }
    /**
     * Summary of getTableName
     * @throws \Exception
     * @return string
     */
    public function getTableName()
    {
        if (empty($this->tableName))
            return null;
        // throw new \Exception("Migration " . static::class . " not set table name(tableName).");
        switch ($this->status) {
            case "public":
                return $this->prefix . $this->tableName;
            case "protected":
                return $this->prefix . $this->tableName;
            case "private":
                if (empty($this->prefix))
                    // throw new \Exception("Migration " . static::class . " not set table prefix(prefix).");
                    return null;
            default:
                break;
        }
        return null;
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
