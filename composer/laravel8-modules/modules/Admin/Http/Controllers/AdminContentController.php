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
        $query = $this->getModel('content')::withCount('children')
            ->where('slug', 'like', '%' . $request->input('slug') . '%')
            ->where('title', 'like', '%' . $request->input('title') . '%')
            ->whereIn('type', $request->filled('type') ? [$request->input('type')] : array_keys(\Arr::get($this->moduleOption, 'content.type')))
            ->whereIn('status', $request->filled('status') ? [$request->input('status')] : array_keys(\Arr::get($this->moduleOption, 'content.status')))
            ->where('parent', $request->input('parent', 0))
            ->whereNull('deleted_at')
            ->orderByDesc('updated_at');
        \Arr::set($this->sqls, 'select_content_list', $query->toRawSql());
        return $this->view('data.content-list', [
            'paginator' => $query->paginate(20),
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
            'content' => new $contentModel
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
        // var_dump($content['slug']);
        // return $this->edit($content->id);
        return $this->edit($content->id);

        // return redirect(str_replace('create', $content->id, $request->path()));
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
