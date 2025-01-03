<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Illuminate\Routing\Controller;

class AdminMetaController extends AdminController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {

        if ($request->method() == 'POST')
            $this->import($request->merge(['meta_id' => $request->input('parent', 0)]));

        $query = $this->getModel('meta')::withCount('children');

        $request->whenFilled('slug', function ($input) use (&$query) {
            $query = $query->where('slug', 'like', "%$input%");
        });
        $request->whenFilled('name', function ($input) use (&$query) {
            $query = $query->where('name', 'like', "%$input%");
        });
        $query = $query
            ->whereIn('type', $request->filled('type') ? [$request->input('type')] : array_keys(\Arr::get($this->moduleOption, 'meta.type')))
            ->whereIn('status', $request->filled('status') ? [$request->input('status')] : array_keys(\Arr::get($this->moduleOption, 'meta.status')))
            ->where('parent', $request->input('parent', 0))
            ->whereNull('deleted_at')
            ->orderBy('order')
            ->orderByDesc('updated_at');
        \Arr::set($this->sqls, 'select_meta_list', $query->toRawSql());
        return $this->view('data.meta-table', [
            'paginator' => $query->paginate(20),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $metaModel = $this->getModel('meta');
        $return = [
            'meta' => new $metaModel(request()->all()),
        ];
        return $this->view('data.meta-item', $return);
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
        $metaModel = $this->getModel('meta');

        $meta = new $metaModel($request->all());
        // $meta->fill($request->all());
        $meta->save();

        return redirect(str_replace('create', $meta->id, $request->path()));
        // return redirect(($this->moduleAlias ?? 'home') . '/update-content/' . $meta->id);
        // return $this->edit($meta->id);

        // return redirect(($this->moduleAlias ?? 'home') . '/update-meta/' . $meta->id)->withInput($meta->toArray());

    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $meta = $this->getModel('meta')::find($id);

        if ($meta['type'] == 'module') {
            $meta['modules'] = $this->getModel('meta')::with(['children'])
                ->where('type', 'module')
                ->where('parent', $meta->id)
                ->get();
            $meta['branches'] = $this->getModel('meta')::with([])
                ->where('type', 'branch')
                ->where('parent', $meta->id)
                ->get();
            $meta['categories'] = $this->getModel('meta')::with(['children'])
                ->where('type', 'category')
                ->where('parent', $meta->id)
                ->get();
            $meta['tags'] = $this->getModel('meta')::with([])
                ->where('type', 'tag')
                ->where('parent', $meta->id)
                ->get();
        }

        $return = [
            'meta' => $meta,
        ];
        return $this->view('data.meta-item', $return);
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

        // 
        $request->merge(['user' => \Auth::id()]);
        // 
        $meta = $this->getModel('meta')::find($id);
        // 
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
    /**
     * Summary of import
     * @param \Illuminate\Http\Request $request
     */
    public function import(Request $request)
    {
        if ($request->method() == 'GET')
            return;
        // $file = $request->file('file');
        // var_dump($file);
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            //
            var_dump($request->file('file'));

            var_dump([
                'path' => $request->file->path(),
                'extension' => $request->file->extension(),
                'getPath' => $request->file->getPath(),
                'getPathInfo' => $request->file->getPathInfo(),
                'getClientOriginalName' => $request->file->getClientOriginalName(),
                'getClientOriginalExtension' => $request->file->getClientOriginalExtension(),
                'getMaxFilesize' => $request->file->getMaxFilesize(),
                'getErrorMessage' => $request->file->getErrorMessage(),
                'getError' => $request->file->getError(),
                'getPathname' => $request->file->getPathname(),
                'getClientMimeType' => $request->file->getClientMimeType(),
            ]);


            $fileModel = $this->getModel("file");
            $file = new $fileModel;
            $file->fill([
                'slug' => basename($request->file->hashName(), '.txt'),
                'name' => $request->file->getClientOriginalName(),
                'extension' => $request->file->getClientOriginalExtension(),
                'type' => $request->file->getClientMimeType(),
                'user' => \Auth::id(),
            ]);
            $file->save();

            $this->getModel('relationship')::insert([
                'meta_id' => $request->input('meta_id', 0),
                'file_id' => $file->id,
            ]);
            var_dump($file);

            $path = $request->file->storeAs('metas/' . $request->file->getClientOriginalExtension(), str_replace(['-', ' ', ':'], '_', $file->created_at) . '_' . $request->file->getClientOriginalName());
            var_dump($path);
            $file->timestamps = false;
            $file->update([
                'path' => $path,
            ]);
            $file->save();
            switch ($file->type) {
                case 'application/json':
                    $fileContent = \Storage::get($path);
                    $this->upsertModelData($request, json_decode($fileContent, true));
                    break;
                case 'md':
                    break;
                default:
                    break;
            }


        } else {
        }
        // return $this->index($request);
    }
    public function list(Request $request)
    {
        $request->validate([
            'ids' => 'required|string',
            'operation' => 'required|string',
        ]);
        $ids = explode('|', $request->input('ids'));
        $return = [
            "list" => $this->getModel('meta')::whereIn('id', $ids)->get(),
            "modules" => $this->getModel('meta')::with([
                'children' => function ($query) {
                    $query->where('type', 'module');
                }
            ])->where('type', 'module')->where('parent', 0)->get(),
        ];

        return $this->view('data.meta-list', $return);
    }
    public function batch(Request $request)
    {
        $request->validate([
            'ids' => 'required|string',
            'operation' => 'required|string',
        ]);
        $ids = explode('|', $request->input('ids'));
        $list = $this->getModel('meta')::whereIn('id', $ids)->get();

        switch ($request->input('operation')) {
            case 'update':
            case 'copy':
            case 'delete':

                break;
            case 'remove':
                $request->whenFilled('module', function ($input) use (&$list) {
                    foreach ($list as $item) {
                        $item->fill(['parent' => $input]);
                        $item->save();
                    }
                });
                break;

            case 'export-json':
                $path = 'metas/' . date_format(now(), 'Y_m_d_H_i_s_ms') . '_metas.json';
                \Storage::put($path, json_encode(["metas" => $list], JSON_UNESCAPED_UNICODE));
                // return response()->download(\Storage::path($path), basename($path), ['content-type' => 'application/json']);

                return \Storage::download($path, basename($path));
                break;
            case 'export-csv':
            case 'export-xlsx':
            case 'export-pdf':
                break;
            default:
                break;
        }

        // var_dump($request->all());
        return $this->list($request);
        // $file = $request->file('file');
        // var_dump($file);

        // return $this->index($request);
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

