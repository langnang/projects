<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MetaController extends Controller
{

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $return = [
            'metas' => $this->getModel('meta')::with(['children'])
                ->whereIn('type', ['category', 'branch', 'module',])
                ->whereNull('deleted_at')
                ->get()
        ];
        return $this->view('meta-form', $return);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $this->validateMeta($request);
        //
        $request->merge(['user' => \Auth::id()]);

        $meta = new $this->getModel('meta')();
        $meta->fill($request->all());
        $meta->save();

        return redirect(($this->moduleAlias ?? 'home') . '/update-content/' . $meta->id);
        // return $this->edit($meta->id);

        // return redirect(($this->moduleAlias ?? 'home') . '/update-meta/' . $meta->id)->withInput($meta->toArray());

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $return = [
            'meta' => $this->getModel('meta')::find($id),
        ];
        return $this->view('meta', $return);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $return = [
            'meta' => $this->getModel('meta')::find($id),
            'metas' => $this->getModel('meta')::with(['children'])
                ->whereIn('type', ['category', 'branch', 'module',])
                ->whereKeyNot($id)
                ->get()
        ];
        return $this->view('meta-form', $return);
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
        $this->validateMeta($request);
        $request->merge(['user' => \Auth::id()]);
        $meta = $this->getModel('meta')::find($id);
        $meta->fill($request->all());
        $meta->save();

        return $this->edit($meta->id);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
        $meta = $this->getModel('meta')::find($id);
        $meta->timestamps = false;
        $meta->update([
            'deleted_at' => now()
        ]);

        \App\Models\Relationship::where('meta_id', request()->input('id'))->delete();

        return redirect(($this->moduleAlias ?? 'home'));
        // return $this->view('index');

    }

    protected function validateMeta(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'type' => 'required|string',
            'status' => 'required|string',
        ]);
    }
}
