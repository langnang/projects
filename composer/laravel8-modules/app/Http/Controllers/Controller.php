<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Controller extends \App\Illuminate\Routing\Controller
{
    protected function view($view = null, $data = [], $mergeData = [])
    {
        // var_dump(__METHOD__);
        // $metaRelations = $this->moduleMeta->relationships()->get();
        // var_dump($metaRelations->toArray());

        // $links = $this->getModel('link')::get();

        // $sql = \Blade::render('SELECT meta_id, cheatsheet_id  FROM `relationships`', $data);
        // $links = \App\Models\Link::with(['relationships'])->limit(1)->toSql();
        // var_dump($links->toArray());
        $return = array_merge($data, [
            // metas[type=category]
            'categories' => \Arr::get(
                $data,
                'categories',
                $this->getModel('meta')::with(['children'])
                    ->where('type', 'category')
                    ->whereIn('status', \Auth::check() ? ['public', 'publish', 'protected', 'private'] : ['public', 'publish'])
                    ->where('parent', $this->moduleMeta['id'])
                    ->whereNull('deleted_at')
                    ->where('name', '!=', '')
                    ->get()
            ),
            // metas[type=tag]
            'tags' => \Arr::get(
                $data,
                'tags',
                $this->getModel('meta')::where('type', 'tag')
                    ->whereIn('status', \Auth::check() ? ['public', 'publish', 'protected', 'private'] : ['public', 'publish'])
                    ->where('parent', $this->moduleMeta['id'])
                    ->whereNull('deleted_at')
                    ->where('name', '!=', '')
                    ->get()
            ),
            // metas[type=module]
            'modules' => \Arr::get(
                $data,
                'modules',
                $this->getModel('meta')::where('type', 'module')
                    ->whereIn('status', \Auth::check() ? ['public', 'publish', 'protected', 'private'] : ['public', 'publish'])
                    ->where('parent', $this->moduleMeta['id'])
                    ->whereNull('deleted_at')
                    ->where('name', '!=', '')
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
                $this->getModel('link')::with(['user', 'relationships'])
                    ->whereIn('type', ['site'])
                    ->whereIn('status', \Auth::check() ? ['public', 'publish', 'protected', 'private'] : ['public', 'publish'])
                    ->whereNull('deleted_at')
                    ->where('title', '!=', '')
                    ->orderByDesc('updated_at')
                    ->limit(20)
                    ->get()
            ),


            'latest_contents' => \Arr::get(
                $data,
                'latest_contents',
                $this->getModel('content')::whereIn('type', ['post'])
                    ->whereIn('status', \Auth::check() ? ['public', 'publish', 'protected', 'private'] : ['public', 'publish'])
                    ->whereNull('deleted_at')
                    ->orderByDesc('updated_at')
                    ->limit(10)
                    ->get()
            ),
            'latest_comments' => \Arr::get(
                $data,
                'latest_comments',
                $this->getModel('comment')::orderByDesc('updated_at')
                    ->whereNull('deleted_at')
                    ->limit(10)
                    ->get()
            ),
        ]);


        if (empty($return['contents'])) {
            $query = $this->getModel('content')::with(['belongsToMeta', 'user'])->whereHas('belongsToMeta', function ($query) {
                $query->where('meta_id', $this->moduleMeta->id);
            });
            if (request()->filled('title')) {
                $query = $query->where('title', 'like', '%' . request()->input('title') . '%');
            }
            $query = $query->where('type', 'post');
            $query = $query->whereIn('status', \Auth::check() ? ['public', 'publish', 'protected', 'private'] : ['public', 'publish']);
            $query = $query->whereNull('deleted_at');
            $query = $query->orderByDesc('updated_at');
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

    protected function getMetasWithModule()
    {
    }
    protected function getContentsWithModule($id)
    {
        return $this->getModel('content')::with(['belongsToMeta'])->whereHas('belongsToMeta', function ($query) use ($id) {
            $query->where('meta_id', $id);
        });
    }
    protected function getLinksWithModule($id)
    {
        return $this->getModel('content')::with(['belongsToMeta'])->whereHas('belongsToMeta', function ($query) use ($id) {
            $query->where('meta_id', $id);
        });
    }
}
