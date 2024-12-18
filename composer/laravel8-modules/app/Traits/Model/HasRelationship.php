<?php

namespace App\Traits\Model;

trait HasRelationship
{

    public function metas()
    {
        return $this
            ->hasMany(\App\Models\Relationship::class, $this->relationshipKey, $this->primaryKey)
            ->leftJoin("metas", "relationships." . "meta_id", '=', "metas.mid");
    }

    public function contents()
    {
        return $this
            ->hasMany(\App\Models\Relationship::class, $this->relationshipKey, $this->primaryKey)
            ->leftJoin("contents", "relationships." . "content_id", '=', "contents.cid");
    }

    public function links()
    {
        return $this
            ->hasMany(\App\Models\Relationship::class, $this->relationshipKey, $this->primaryKey)
            ->leftJoin("links", "relationships." . "link_id", '=', "links.lid");
    }

    public function relationships()
    {
        return $this->hasMany(\App\Models\Relationship::class, $this->relationshipKey, $this->primaryKey);
    }

    public function logs()
    {
    }
}
