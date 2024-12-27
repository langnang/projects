<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Controller extends \App\Illuminate\Routing\Controller
{
    protected $metaModel = \App\Models\Meta::class;
    protected $contentModel = \App\Models\Content::class;
    protected $linkModel = \App\Models\Link::class;
    protected $fieldModel = \App\Models\Field::class;
    protected $commentModel = \App\Models\Comment::class;
    protected function view($view = null, $data = [], $mergeData = [])
    {
        // $sql = \Blade::render('SELECT meta_id, cheatsheet_id  FROM `relationships`', $data);
        // $links = \App\Models\Link::with(['relationships'])->limit(1)->toSql();
        // var_dump($links);
        $return = array_merge($data, [
            'contents' => \Arr::get(
                $data,
                'contents',
                $this->hasModule()
                ? []
                : $this->contentModel::with(['user'])
                    ->whereIn('type', ['post'])
                    ->whereIn('status', \Auth::check() ? ['public', 'publish', 'protected', 'private'] : ['public', 'publish'])
                    ->whereNull('deleted_at')
                    ->orderByDesc('updated_at')
                    ->paginate()
            ),


            'links' => \Arr::get(
                $data,
                'links',
                $this->hasModule()
                ? $this->linkModel::with(['user', 'relationships'])
                    ->whereIn('type', ['site'])
                    ->whereIn('status', \Auth::check() ? ['public', 'publish', 'protected', 'private'] : ['public', 'publish'])
                    ->whereNull('deleted_at')
                    ->orderByDesc('updated_at')->limit(20)->get()
                : $this->linkModel::with(['user', 'relationships'])
                    ->whereIn('type', ['site'])
                    ->whereIn('status', \Auth::check() ? ['public', 'publish', 'protected', 'private'] : ['public', 'publish'])
                    ->whereNull('deleted_at')
                    ->orderByDesc('updated_at')->limit(20)->get()
            ),
            'categories' => \Arr::get(
                $data,
                'categories',
                $this->hasModule()
                ? []
                : $this->metaModel::with(['children'])
                    ->where('type', 'category')
                    ->whereIn('status', \Auth::check() ? ['public', 'publish', 'protected', 'private'] : ['public', 'publish'])
                    ->where('parent', 0)
                    ->whereNull('deleted_at')
                    ->get()
            ),
            'tags' => \Arr::get(
                $data,
                'tags',
                $this->hasModule()
                ? []
                : $this->metaModel::where('type', 'tag')
                    ->whereIn('status', \Auth::check() ? ['public', 'publish', 'protected', 'private'] : ['public', 'publish'])
                    ->where('parent', 0)
                    ->whereNull('deleted_at')
                    ->get()
            ),
            'latest_contents' => \Arr::get(
                $data,
                'latest_contents',
                $this->hasModule()
                ? []
                : $this->contentModel::whereIn('type', ['post'])
                    ->whereIn('status', \Auth::check() ? ['public', 'publish', 'protected', 'private'] : ['public', 'publish'])
                    ->whereNull('deleted_at')
                    ->orderByDesc('updated_at')
                    ->limit(10)
                    ->get()
            ),
            'latest_comments' => \Arr::get(
                $data,
                'latest_comments',
                $this->hasModule()
                ? []
                : $this->commentModel::orderByDesc('updated_at')
                    ->whereNull('deleted_at')
                    ->limit(10)
                    ->get()
            ),
        ]);
        return parent::view($view, $return, $mergeData);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return $this->view('index');
    }
}
