<?php

namespace App\Illuminate\Routing;

use Illuminate\Support\Facades\View;

abstract class Controller
{
    protected $module;
    protected $moduleName;
    protected $moduleAlias;
    protected $moduleConfig;
    protected $moduleOption;
    public function __construct()
    {
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
        $module = $this->module;
        // $module = \Module::find($this->moduleName);
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
            'module' => $this->getModuleAttributes(),
            'layout' => null,
            'view' => null,
            // 'layout' => "layouts.master",
        ], is_array($view) ? $view : ['view' => $view], $data);
        if (!isset($return['view']))
            return abort(403);
        $return['layout'] = $this->config('framework') . '.' . $return['view'];
        $return['view'] = $this->moduleAlias . '::' . $this->config('framework') . '.' . $return['view'];
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

    public function view_index($midOrSlug = null)
    {
        $return = [
            'contents' => [
                'paginator' => \App\Models\Content::factory()->times(15)->make(),
                'hottest' => \App\Models\Content::factory()->times(10)->make(),
            ],
            'metas' => [
                'categories' => \App\Models\Meta::factory()->times(10)->make(),
                'tags' => \App\Models\Meta::factory()->times(10)->make(),
                'groups' => \App\Models\Meta::factory()->times(10)->make(),
                'collections' => \App\Models\Meta::factory()->times(10)->make(),
            ],
            'comments' => [
                'latest' => \App\Models\Comment::factory()->times(10)->make(),
            ],
            'children' => $this->config('hasChildren') ? \App\Models\Meta::factory()->times(10)->make() : null,
        ];

        return $this->view('index', $return);
    }
    public function view_content($cidOrSlug)
    {
        $return = [
            'content' => \App\Models\Content::factory()->times(1)->make()->first(),
            'contents' => [
                'paginator' => \App\Models\Content::factory()->times(15)->make(),
                'hottest' => \App\Models\Content::factory()->times(10)->make(),
            ],
            'metas' => [
                'categories' => \App\Models\Meta::factory()->times(10)->make(),
                'tags' => \App\Models\Meta::factory()->times(10)->make(),
                'groups' => \App\Models\Meta::factory()->times(10)->make(),
                'collections' => \App\Models\Meta::factory()->times(10)->make(),
            ],
            'comments' => [
                'latest' => \App\Models\Comment::factory()->times(10)->make(),
            ],
            'children' => $this->config('hasChildren') ? \App\Models\Meta::factory()->times(10)->make() : null,
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
