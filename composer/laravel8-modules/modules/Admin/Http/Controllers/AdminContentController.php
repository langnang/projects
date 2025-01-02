<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Illuminate\Routing\Controller;

class AdminContentController extends AdminController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {

        // var_dump($this->getModel('content')::belongsToMany(\App\Models\Meta::class, 'relationships', 'content_id', 'meta_id'));

        // $query = $this->getModel('content')::with([
        //     'relationships',
        //     'belongsToMeta' => function ($query) {
        //         $query->wherePivot('meta_id', $this->moduleMeta->id);
        //     }
        // ])->withCount('children')
        //     ->where('slug', 'like', '%' . $request->input('slug') . '%')
        //     ->where('title', 'like', '%' . $request->input('title') . '%')
        //     ->whereIn('type', $request->filled('type') ? [$request->input('type')] : array_keys(\Arr::get($this->moduleOption, 'content.type')))
        //     ->whereIn('status', $request->filled('status') ? [$request->input('status')] : array_keys(\Arr::get($this->moduleOption, 'content.status')))
        //     ->where('parent', $request->input('parent', 0))
        //     ->whereNull('deleted_at')
        //     ->orderByDesc('updated_at');
        \DB::enableQueryLog();
        \DB::flushQueryLog();
        $paginator = $this->getModel('content')::with([
            'relationships',
            'belongsToMeta'
        ])->whereHas('belongsToMeta', function ($query) {
            $query->where('meta_id', $this->moduleMeta->id);
        })->withCount('children')
            ->where('slug', 'like', '%' . $request->input('slug') . '%')
            ->where('title', 'like', '%' . $request->input('title') . '%')
            ->whereIn('type', $request->filled('type') ? [$request->input('type')] : array_keys(\Arr::get($this->moduleOption, 'content.type')))
            ->whereIn('status', $request->filled('status') ? [$request->input('status')] : array_keys(\Arr::get($this->moduleOption, 'content.status')))
            ->where('parent', $request->input('parent', 0))
            ->whereNull('deleted_at')
            ->orderByDesc('updated_at')
            ->paginate(20);

        $this->setSqls('select_content_list', \DB::getQueryLog());
        \DB::disableQueryLog();
        return $this->view('data.content-list', [
            'paginator' => $paginator,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $contentModel = $this->getModel('content');
        $return = [
            'content' => new $contentModel(request()->all())
        ];
        return $this->view('data.content-item', $return);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $this->validateContent($request);
        //
        $request->merge(['user' => \Auth::id()]);
        $contentModel = $this->getModel('content');

        $content = new $contentModel;
        $content->fill($request->all());
        $content->save();
        if (empty($content->slug)) {
            $content->slug = $content->id;
            $content->save();
        }
        $this->getModel('relationship')::where("content_id", $content->id)->delete();
        $this->getModel('relationship')::insert([
            "meta_id" => $this->moduleMeta->id,
            "content_id" => $content->id,
        ]);
        // var_dump($content['slug']);
        // return $this->edit($content->id);
        // return $this->edit($content->id);

        return redirect(str_replace('create', $content->id, $request->path()));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $return = [
            'content' => $this->getModel('content')::with(['fields', 'relationships'])->find($id),
            // 'contents' => $this->getModel('content')::with(['children'])
            //     ->whereIn('type', ['template',])
            //     ->whereKeyNot($id)
            //     ->whereNull('deleted_at')
            //     ->get()
        ];
        return $this->view('data.content-item', $return);
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
        $this->validateContent($request);
        $request->merge(['user' => \Auth::id()]);
        $content = $this->getModel('content')::find($id);
        $content->fill($request->all());
        if (empty($content->slug)) {
            $content->slug = $content->id;
        }
        $content->save();

        $this->getModel('relationship')::where("content_id", $content->id)->delete();
        $this->getModel('relationship')::insert([
            "meta_id" => $this->moduleMeta->id,
            "content_id" => $content->id,
        ]);

        return $this->edit($content->id);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
        $content = $this->getModel('content')::find($id);
        $content->timestamps = false;
        $content->update([
            'deleted_at' => now()
        ]);

        \App\Models\Relationship::where('content_id', request()->input('id'))->delete();

        return redirect(($this->moduleAlias ?? 'home'));
        // return $this->view('index');

    }

    protected function validateContent(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'type' => 'required|string',
            'status' => 'required|string',
        ]);
    }
}

