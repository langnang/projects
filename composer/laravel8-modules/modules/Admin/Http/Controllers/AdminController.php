<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class AdminController extends \App\Illuminate\Routing\Controller
{
    protected $metaClass;
    protected $contentClass;
    protected $linkClass;
    protected $fieldClass;
    protected $commentClass;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return $this->view('index');
    }

    protected function getMetaClass()
    {
        return $this->metaClass ?? \App\Models\Meta::class;
    }
    protected function getContentClass()
    {
        return $this->contentClass ?? \App\Models\Content::class;
    }
    protected function getLinkClass()
    {
        return $this->linkClass ?? \App\Models\Link::class;
    }
    protected function getFieldClass()
    {
        return $this->fieldClass ?? \App\Models\Field::class;
    }
    protected function getCommentClass()
    {
        return $this->commentClass ?? \App\Models\Comment::class;
    }
}

