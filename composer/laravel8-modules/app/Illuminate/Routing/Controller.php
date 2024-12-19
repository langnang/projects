<?php

namespace App\Illuminate\Routing;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

abstract class Controller extends \Illuminate\Routing\Controller
{
    protected $module;
    protected $moduleName;
    protected $moduleAlias;
    protected $moduleConfig;
    protected $moduleOption;
    protected $moduleMeta;

    public function __construct()
    {
        // $this->middleware('auth');
        if (empty($this->moduleName)) {
            if (preg_match('/^Modules\\\\(\w*)\\\\Http/i', static::class, $moduleMatches)) {
                $this->moduleName = $moduleMatches[1];
            }
        }
        if (empty($moduleName = $this->moduleName))
            return;
        $this->module = $module = \Module::find($moduleName);
        if (empty($module))
            return;
        $this->moduleAlias = $module->getAlias();
        $this->moduleConfig = config($this->moduleAlias);

        $this->moduleMeta = \App\Models\Meta::where('slug', 'module:' . $this->moduleAlias)->first();

        $options = \App\Models\Option::where('name', 'like', 'global.%')
            ->orWhere('name', 'like', 'meta.%')
            ->orWhere('name', 'like', 'content.%')
            ->orWhere('name', 'like', $this->moduleAlias . '.%')
            ->get()->toArray();
        foreach ($options as $option) {
            \Arr::set($this->moduleOption, $option['name'], $option['value'], );
        }

        // var_dump($this->moduleOption);
        // $this->moduleOption = \App\Models\Option::find();
        // dump($module->getAlias());
        // dump($this->module);
        // dump($this->moduleName);
        // dump($this->moduleAlias);
        // dump($this->moduleConfig);
        // dump($this->moduleOption);
        // dump(__CLASS__);
    }
    /**
     * Summary of config
     * @param mixed $key
     * @param mixed $default
     * @return mixed
     */
    public function config($key, $default = null)
    {
        return $this->moduleName ? \Arr::get($this->moduleConfig, $key, $default) : config($key, $default);
    }
    /**
     * Summary of view
     * @param mixed $view
     * @param mixed $data
     * @param mixed $mergeData
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function view($view = null, $data = [], $mergeData = [])
    {
        $return = array_merge([

            '$options' => [],
            '$servers' => [],
            '$constants' => [],
            '$variables' => [
                'route' => [
                    'method' => request()->method(),
                    'url' => request()->url(),
                    'fullUrl' => request()->fullUrl(),
                    'path' => request()->path(),
                    'pathInfo' => request()->getPathInfo(),
                ],
                'request' => request()->all(),
            ],
            '$route' => [
                'method' => request()->method(),
                'url' => request()->url(),
                'fullUrl' => request()->fullUrl(),
                'path' => request()->path(),
                'pathInfo' => request()->getPathInfo(),
            ],
            '$request' => request()->all(),
            '$user' => Auth::check() ? Auth::user() : null,
            'module' => $this->getModuleAttributes(),
            'layout' => null,
            'view' => null,
            'contents' => [
                'paginator' => \Arr::get($data, 'contents.paginator', \App\Models\Content::factory()->times(15)->make()),
                'hottest' => \Arr::get($data, 'contents.hottest', \App\Models\Content::factory()->times(10)->make()),
            ],
            'metas' => [
                'categories' => \Arr::get($data, 'metas.categories', \App\Models\Meta::factory()->times(10)->make()),
                'tags' => \Arr::get($data, 'metas.tags', \App\Models\Meta::factory()->times(10)->make()),
                'groups' => \Arr::get($data, 'metas.groups', \App\Models\Meta::factory()->times(10)->make()),
                'collections' => \Arr::get($data, 'metas.collections', \App\Models\Meta::factory()->times(10)->make()),
            ],
            'comments' => [
                'latest' => \Arr::get($data, 'comments.latest', \App\Models\Comment::factory()->times(10)->make()),
            ],
            'children' => $this->config('hasChildren') ? \App\Models\Meta::factory()->times(10)->make() : null,
            // 'layout' => "layouts.master",
        ], is_array($view) ? $view : ['view' => $view], $data);
        if (!isset($return['view']))
            return \abort(403);
        if ($this->moduleName) {
            $return['layout'] = $this->config('framework') . '.' . $return['view'];
            $return['view'] = $this->moduleAlias . '::' . $this->config('framework') . '.' . $return['view'];
        }
        if (!View::exists($return['view'])) {
            $return['view'] = $return['layout'];
        }
        if (env('WEB_CONSOLE')) {
            echo "<script>window.\$app=" . json_encode($return, JSON_UNESCAPED_UNICODE) . ";</script>";
            echo "<script>console.log('window.\$app',window.\$app);</script>";
        }
        // dump($return);
        return view($return['view'], $return, $mergeData);
    }
    public function getModuleAttributes()
    {
        if (empty($this->moduleName))
            return;

        $module = $this->module;
        return array_merge($this->moduleConfig, [
            'alias' => $this->moduleAlias,
            'config' => $this->moduleConfig,
            'option' => $this->moduleOption,
            'wrapperMeta' => $this->moduleMeta,
            'lowerName' => $module->getLowerName(),
            'studlyName' => $module->getStudlyName(),
            'path' => $module->getPath(),
            'extraPath' => $module->getExtraPath('Public'),
            'enabled' => $module->isEnabled(),
            'disabled' => $module->isDisabled(),
            'status' => $module->IsStatus(true),
            'requires' => $module->getRequires(),
        ]);
    }

    public function view_index($idOrSlug = null)
    {
        $return = [];

        return $this->view('index', $return);
    }
    public function view_content($idOrSlug)
    {
        $return = [
            'content' => \App\Models\Content::factory()->times(1)->make()->first(),
        ];

        return $this->view('content', $return);
    }

    public function view_admin()
    {
    }

    public function view_market()
    {
    }
}
