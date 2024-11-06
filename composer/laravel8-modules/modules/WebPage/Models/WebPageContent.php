<?php

namespace Modules\WebPage\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebPageContent extends Model
{
    use HasFactory;
    protected $table = "webpage_contents";

    protected $primaryKey = "cid";
    protected $fillable = [
        "slug",
        "title",
        "ico",
        "description",
        "html",
        "style",
        "script",
        "parent",
        "count",
        "order",
        "user",
        "template",
    ];

    protected static function newFactory()
    {
        return \Modules\WebPage\Database\factories\WebPageContentFactory::new();
    }
}
