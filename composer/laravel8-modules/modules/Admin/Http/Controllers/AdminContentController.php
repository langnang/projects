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
    public function index()
    {
        return $this->view('data.content-list', [
            'paginator' => $this->getContentClass()::paginate(20),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $return = [
            'contents' => $this->getContentClass()::with(['children'])
                ->whereIn('type', ['template',])
                ->whereNull('deleted_at')
                ->get()
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

        $content = new $this->getContentClass();
        $content->fill($request->all());
        $content->save();

        // return $this->edit($content->id);

        return redirect(($this->moduleAlias ?? 'home') . '/contents/' . $content->id);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $return = [
            'content' => $this->getContentClass()::with(['fields', 'relationships'])->find($id),
            // 'contents' => $this->getContentClass()::with(['children'])
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
        $content = $this->getContentClass()::find($id);
        $content->fill($request->all());
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
        $content = $this->getContentClass()::find($id);
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

