<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Controller extends \App\Illuminate\Routing\Controller
{
    protected function view($view = null, $data = [], $mergeData = [])
    {
        $return = array_merge($data, [
            'contents' => \Arr::get(
                $data,
                'contents',
                \App\Models\Content::with(['user'])
                    ->whereIn('type', ['post'])
                    ->whereIn('status', \Auth::check() ? ['public', 'publish', 'protected', 'private'] : ['public', 'publish'])
                    ->whereNull('deleted_at')
                    ->orderByDesc('updated_at')
                    ->paginate()
            ),
            'links' => \Arr::get(
                $data,
                'contents',
                \App\Models\Link::with(['user'])
                    ->whereIn('type', ['site'])
                    ->whereIn('status', \Auth::check() ? ['public', 'publish', 'protected', 'private'] : ['public', 'publish'])
                    ->whereNull('deleted_at')
                    ->orderByDesc('updated_at')
                    ->limit(20)
                    ->get()
            ),
            'categories' => \Arr::get(
                $data,
                'contents',
                \App\Models\Meta::with(['children'])
                    ->where('type', 'category')
                    ->whereIn('status', \Auth::check() ? ['public', 'publish', 'protected', 'private'] : ['public', 'publish'])
                    ->whereNull('deleted_at')
                    ->get()
            ),
            'tags' => \Arr::get(
                $data,
                'contents',
                \App\Models\Meta::where('type', 'tag')
                    ->whereIn('status', \Auth::check() ? ['public', 'publish', 'protected', 'private'] : ['public', 'publish'])
                    ->whereNull('deleted_at')
                    ->get()
            ),
            'latest_contents' => \Arr::get(
                $data,
                'contents',
                \App\Models\Content::whereIn('type', ['post'])
                    ->whereIn('status', \Auth::check() ? ['public', 'publish', 'protected', 'private'] : ['public', 'publish'])
                    ->whereNull('deleted_at')
                    ->orderByDesc('updated_at')
                    ->limit(10)
                    ->get()
            ),
            'latest_comments' => \Arr::get(
                $data,
                'contents',
                \App\Models\Comment::orderByDesc('updated_at')
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
