<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContentController extends Controller
{

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $return = [
            'contents' => $this->contentModel::with(['children'])
                ->whereIn('type', ['template',])
                ->whereNull('deleted_at')
                ->get()
        ];
        return $this->view('content-form', $return);
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

        $content = new $this->contentModel();
        $content->fill($request->all());
        $content->save();

        // return $this->edit($content->id);

        return redirect(($this->moduleAlias ?? 'home') . '/update-content/' . $content->id);

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $content = $this->contentModel::with(['contents', 'links'])->find($id);
        if (empty($content))
            abort(404);
        $return = [
            'content' => $content,
        ];
        return $this->view('content', $return);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $return = [
            'content' => $this->contentModel::find($id),
            'contents' => $this->contentModel::with(['children'])
                ->whereIn('type', ['template',])
                ->whereKeyNot($id)
                ->whereNull('deleted_at')
                ->get()
        ];
        return $this->view('content-form', $return);
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
        $content = $this->contentModel::find($id);
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
        $content = $this->contentModel::find($id);
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
