<?php

namespace Modules\Home\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Controller;
use Modules\Home\Models\HomeContent;

class HomeController extends \App\Http\Controllers\Controller
{
    protected $module = "Home";
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        var_dump(123);
        return view('home::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('home::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('home::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('home::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function select_latest_module_contents(Request $request)
    {
    }
    public function view_index(Request $request)
    {
        $request->merge(["page_size" => 30]);
        $return = [
            'view' => 'index',
            "tabs" => [
                // 'home-latest' => \App\Models\Content::where([['type', 'post'], ['status', 'publish']])->latest('updated_at')->paginate(30),
                'home-latest' => $this->select_list($request, 'content', ['where' => [['type', 'post'], ['status', 'publish'],], "orderBy" => ['updated_at', 'asc']]),
                // 'home-hottest' => \App\Models\Content::where([['type', 'post'], ['status', 'publish']])->orderBy('views', 'desc')->paginate(30),
                'home-hottest' => $this->select_list($request, 'content', ['where' => [['type', 'post'], ['status', 'publish'],], "orderBy" => ['views', 'asc']]),
                'nofield-latest' => \App\Models\Content::doesntHave('fields')->where([['type', 'post'], ['status', 'publish']])->latest('updated_at')->paginate(30),
                'nofield-hottest' => \App\Models\Content::doesntHave('fields')->where([['type', 'post'], ['status', 'publish']])->orderBy('views', 'asc')->paginate(30),
            ]
        ];
        foreach (\Module::all() ?? [] as $moduleName => $module) {
            if (\Module::isEnabled($moduleName) && !in_array($moduleName, config("home.view_index.ignore_modules") ?? [])) {
                if ($moduleName == 'Home')
                    continue;

                $moduleSlug = config(strtolower($moduleName) . ".slug") ?? strtolower($moduleName);

                // $return['tabs'][$moduleSlug . '-toplist'] = new Paginator(Content::factory(30)->raw([], ), 30, 1);
                // $return['tabs'][$moduleSlug . '-latest'] = new Paginator(Content::factory(30)->raw([], ), 30, 1);
                // $return['tabs'][$moduleSlug . '-latest'] = \App\Models\Content::latest_updated(30);
                $return['tabs'][$moduleSlug . '-latest'] = $this->select_list($request, 'content', [
                    'with' => ['fields'],
                    'where' => [['type', 'post'], ['status', 'publish'],],
                    'whereHas' => [
                        'fields',
                        function ($query) use ($moduleSlug) {
                            $query->where([['name', 'module_' . $moduleSlug]]);
                        }
                    ],
                    'orderBy' => ['updated_at', 'asc'],
                ]);
                $return['tabs'][$moduleSlug . '-hottest'] = $this->select_list($request, 'content', [
                    'with' => ['fields'],
                    'where' => [['type', 'post'], ['status', 'publish'],],
                    'whereHas' => [
                        'fields',
                        function ($query) use ($moduleSlug) {
                            $query->where([['name', 'module_' . $moduleSlug]]);
                        }
                    ],
                    'orderBy' => ['views', 'asc'],
                ]);
                // \App\Models\Content::whereHas('fields', function ($query) use ($moduleSlug) {
                //     $query->where([['name', 'module_' . $moduleSlug]]);
                // })
                //     ->where([['type', 'post'], ['status', 'publish']])
                // ->latest('updated_at')
                // ->paginate(30);
                // \App\Models\Field::whereHas('content', function ($query) {
                //     $query->where([['type', 'post'], ['status', 'publish']]);
                // })
                //     ->where([['name', 'module_' . $moduleSlug]])
                //     ->latest('updated_at')
                //     ->paginate(30);
                // $return['tabs'][$moduleSlug . '-hottest'] = new Paginator(Content::factory(30)->raw([], ), 30, 1);
                // $return['tabs'][$moduleSlug . '-recommend'] = new Paginator(Content::factory(30)->raw([], ), 30, 1);
                // $return['tabs'][$moduleSlug . '-collection'] = new Paginator(Content::factory(30)->raw([], ), 30, 1);
            }

        }

        return $this->view($return);
    }
}
