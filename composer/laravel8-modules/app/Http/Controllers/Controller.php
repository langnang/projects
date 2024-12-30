<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Controller extends \App\Illuminate\Routing\Controller
{
    protected $metaModel = \App\Models\Meta::class;
    protected $contentModel;
    protected $linkModel = \App\Models\Link::class;
    protected $fieldModel = \App\Models\Field::class;
    protected $commentModel = \App\Models\Comment::class;
    protected function view($view = null, $data = [], $mergeData = [])
    {

        // $metaRelations = $this->moduleMeta->relationships()->get();
        // var_dump($metaRelations->toArray());

        // $links = $this->linkModel::get();

        // $sql = \Blade::render('SELECT meta_id, cheatsheet_id  FROM `relationships`', $data);
        // $links = \App\Models\Link::with(['relationships'])->limit(1)->toSql();
        // var_dump($links->toArray());
        $return = array_merge($data, [
            // metas[type=category]
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
            // metas[type=tag]
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
            // metas[type=module]
            'modules' => \Arr::get(
                $data,
                'modules',
                $this->hasModule()
                ? []
                : $this->metaModel::where('type', 'module')
                    ->whereIn('status', \Auth::check() ? ['public', 'publish', 'protected', 'private'] : ['public', 'publish'])
                    ->where('parent', 0)
                    ->whereNull('deleted_at')
                    ->get()
            ),
            // contents[type=post]
            // contents[type=template]
            // contents[type=page]
            'contents' => \Arr::get(
                $data,
                'contents',

            ),
            // contents[type=post]

            // links[type=site]
            // 根据模块对应的 MetaID 
            'links' => \Arr::get(
                $data,
                'links',
                ($this->hasModule()
                ? $this->moduleMeta->links()
                : $this->linkModel::with(['user', 'relationships']))
                    ->whereIn('type', ['site'])
                    ->whereIn('status', \Auth::check() ? ['public', 'publish', 'protected', 'private'] : ['public', 'publish'])
                    ->whereNull('deleted_at')
                    ->where('title', '!=', '')
                    ->orderByDesc('updated_at')->limit(20)->get()
            ),


            'latest_contents' => \Arr::get(
                $data,
                'latest_contents',
                ($this->hasModule()
                ? $this->moduleMeta->contents()
                : $this->contentModel::whereIn('type', ['post']))
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


        if (empty($return['contents'])) {
            $query = $this->hasModule()
                ? (empty($this->contentModel)
                    ? \App\Models\Content::contents()
                    : $this->contentModel::with(['user']))
                : $this->contentModel::with(['user']);



            if (request()->filled('title'))
                $query = $query->where('title', 'like', '%' . request()->input('title') . '%');
            $query = $query->where('type', 'post');
            $query = $query->whereIn('status', \Auth::check() ? ['public', 'publish', 'protected', 'private'] : ['public', 'publish']);
            $query = $query->whereNull('deleted_at');
            $query = $query->orderByDesc('updated_at');
            \Arr::set($this->sqls, 'select_content_list', $query->toRawSql());
            $query = $query->paginate();
            $return['contents'] = $query;
            unset($query);
        }

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
    public function welcome()
    {
        return $this->view('welcome');
    }

    protected function select_content_list(Request $request)
    {

    }
}
