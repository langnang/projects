<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class AdminController extends \App\Illuminate\Routing\Controller
{
    protected $childModuleController;
    protected $adminModule;
    public function __construct($moduleName = null)
    {
        if (!empty($moduleName))
            $this->moduleName = \Str::studly($moduleName);

        // $this->middleware('auth');
        if (empty($this->moduleName)) {
            if (preg_match('/^admin\/modules\/(\w*)/i', request()->path(), $moduleMatches)) {
                $this->moduleName = \Str::studly($moduleMatches[1]);
            } else {
                $this->moduleName = 'Home';
            }
        }
        $moduleName = $this->moduleName;
        if (in_array($moduleName, ['Home'])) {
            $this->moduleAlias = 'home';
            $this->moduleConfig = ['name' => "Home", 'nameCn' => "首页"];
        } else if (!empty($moduleName)) {
            $this->module = $module = \Module::find($moduleName);
            $this->moduleAlias = $module->getAlias();
            $this->moduleConfig = config($this->moduleAlias);
        }

        $this->moduleMeta = $this->moduleName ? \App\Models\Meta::where('slug', 'module:' . $this->moduleAlias)->first() : new \App\Models\Meta(['id' => 0]);

        $this->queryModuleOption();
        $this->childModuleController = new \App\Http\Controllers\Controller('Home');
        $this->adminModule = new \App\Http\Controllers\Controller('Admin');
    }
    protected function view($view = null, $data = [], $mergeData = [])
    {
        $return = array_merge($data, [
            'view' => $view,
            'adminModule' => array_merge($this->adminModule->getModuleAttributes(), [
                'categories' => $this->getModel('meta')::with([
                    'children' => function ($query) {
                        return $query->orderBy('order');
                    }
                ])
                    ->where('type', 'category')
                    ->whereIn('status', \Auth::check() ? ['public', 'publish', 'protected', 'private'] : ['public', 'publish'])
                    ->where('parent', $this->adminModule->moduleMeta->id)
                    ->whereNull('deleted_at')
                    ->where('name', '!=', '')
                    ->orderBy('order')
                    ->get(),
                'active_category' => $this->getModel('meta')::where('slug', \Str::replace('/', ':', request()->path()))->first(),
            ]),
            'childModule' => $this->childModuleController->getModuleAttributes(),
        ]);
        if (empty($return['layout'])) {
            $return['layout'] = 'admin::' . $this->config('view.framework', ) . '.' . $return['view'];
            // var_dump($return['layout']);
            if (!View::exists($return['layout'])) {
                $return['layout'] = $return['view'];
            }
            // var_dump($return['layout']);
            $return['view'] = $this->moduleAlias . '::' . $this->config('view.framework') . '.' . $return['view'];
            // var_dump($return['view']);
        }
        if (!View::exists($return['view'])) {
            $return['view'] = $return['layout'];
        }
        return parent::view($return['view'], $return, $mergeData);
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        return $this->view('index');
    }

    protected function getMetasWithModule()
    {
    }
    protected function getContentsWithModule()
    {
    }
    protected function getLinksWithModule()
    {
    }

    protected function upsertData(Request $request, ...$values)
    {
        var_dump(__METHOD__);
        var_dump($request->all());
        var_dump($values);

        foreach (array_merge([$this->moduleAlias], array_keys($this->getModel())) as $modelKey) {
            var_dump($modelKey);
            var_dump(\Str::plural($modelKey));
        }
    }
}

