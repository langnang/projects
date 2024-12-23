<?php

namespace App\Illuminate\Routing;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Relations\HasMany;

abstract class Controller extends \Illuminate\Routing\Controller
{
    protected $module;
    protected $moduleName;
    protected $moduleAlias;
    protected $moduleConfig;
    protected $moduleOption;
    protected $moduleMeta;
    protected $user;
    protected $sqls = [];

    protected $models = [
        'content' => \App\Models\Content::class,
        'meta' => \App\Models\Meta::class,
        'link' => \App\Models\Link::class,
        'conmment' => \App\Models\Comment::class,
    ];

    public function __construct()
    {
        // $this->middleware('auth');
        if (empty($this->moduleName)) {
            if (preg_match('/^Modules\\\\(\w*)\\\\Http/i', static::class, $moduleMatches)) {
                $this->moduleName = $moduleMatches[1];
            }
        }
        if (!empty($moduleName = $this->moduleName)) {
            $this->module = $module = \Module::find($moduleName);
            $this->moduleAlias = $module->getAlias();
            $this->moduleConfig = config($this->moduleAlias);
        }

        $this->moduleMeta = $this->moduleName ? \App\Models\Meta::where('slug', 'module:' . $this->moduleAlias)->first() : new \App\Models\Meta(['id' => 0]);

        $this->queryModuleOption();

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
    protected function config($key, $default = null)
    {
        return $this->moduleName ? \Arr::get($this->moduleConfig, $key, $default) : config($key, $default);
    }
    /**
     * Summary of queryModuleOption
     * @return void
     */
    protected function queryModuleOption()
    {
        $builder = \App\Models\Option::where('name', 'like', 'global.%');
        foreach ($this->models ?? [] as $tableKey => $tableModel) {
            $builder = $builder->orWhere('name', 'like', $tableKey . '.%');
        }
        if ($this->moduleName)
            $builder = $builder->orWhere('name', 'like', $this->moduleAlias . '.%');
        \Arr::set($this->sqls, 'select_option_list', $builder->toRawSql());
        $options = $builder->get()->toArray();
        // var_dump($options);
        foreach ($options as $option) {
            \Arr::set($this->moduleOption, $option['name'], $option['value'], );
        }
    }
    /**
     * Summary of option
     * @param mixed $key
     * @param mixed $default
     * @return mixed
     */
    protected function option($key, $default = null)
    {
        return \Arr::get($this->moduleOption, $key, config($key, $default));
    }
    /**
     * Summary of view
     * @param mixed $view
     * @param mixed $data
     * @param mixed $mergeData
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Illuminate\Contracts\View\Factory
     * @return \Illuminate\Contracts\View\View
     */
    protected function view($view = null, $data = [], $mergeData = [])
    {
        $return = array_merge(
            [
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
                '$sqls' => $this->sqls,
                'module' => $this->getModuleAttributes(),
                'options' => $this->moduleOption,
                'layout' => null,
                'view' => null,
                'children' => $this->config('hasChildren') ? \App\Models\Meta::factory()->times(10)->make() : null,
                // 'layout' => "layouts.master",
            ],
            $this->getTableData($data),
            is_array($view) ? $view : ['view' => $view],
            $data
        );
        if (!isset($return['view']))
            abort(403);
        if (empty($return['layout'])) {
            $return['layout'] = $this->config('view.framework', ) . '.' . $return['view'];
            if (!View::exists($return['layout'])) {
                $return['layout'] = $return['view'];
            }
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
    protected function response()
    {
    }
    protected function getModuleAttributes()
    {
        if (empty($this->moduleName))
            return;

        $module = $this->module;
        return array_merge($this->moduleConfig, [
            'alias' => $this->moduleAlias,
            'config' => $this->moduleConfig,
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
    protected function view_index($idOrSlug = null)
    {
        $return = [];

        return $this->view('index', $return);
    }

    protected function view_model($model, $idOrSlug = null)
    {
        if (!in_array($model, array_keys($this->models)))
            abort(404);
        $id = $idOrSlug;
        if (is_numeric($idOrSlug)) {
        }
        $modelClass = $this->models[$model];
        $view = $model;
        if (in_array($idOrSlug, ['create-item', 'update-item', 'insert-item'])) {
            $view .= '-form';
        }
        $return = [
            $model => $modelClass::with([
                'children',
                'contents'
            ])

                ->find($id)
        ];
        return $this->view($view, $return);
    }

    protected function crud_model($model, $idOrSlug = null)
    {
        if (!in_array($model, array_keys($this->models)))
            abort(404);
        $modelClass = $this->models[$model];
        $this->validateModel($model, $idOrSlug);
        request()->merge(['user' => Auth::id()]);
        if (!request()->filled('parent')) {
            request()->merge(['parent' => $this->moduleMeta->id]);
        }
        $method = explode('-', $idOrSlug);
        array_splice($method, 1, 0, $model);
        $method = implode('_', $method);
        // var_dump($method);

        // var_dump(request()->all());
        switch ($method) {
            case 'create_meta_item':
            case 'insert_meta_item':
                $meta = new $modelClass(request()->all());
                $meta->save();
                break;
            case 'update_meta_item':
                break;
            default:
                break;
        }
        return redirect($this->moduleAlias);
        // return $this->sendFailedModelResponse();

        // return $this->view($view, $return);
    }

    protected function validateModel($model, $idOrSlug = null)
    {
        request()->validate($this->option($model . '.' . $idOrSlug . '.validate', []));
    }
    protected function sendFailedModelResponse()
    {
        throw ValidationException::withMessages([
            'name' => [trans('auth.failed')],
        ]);
    }
    protected function view_meta($idOrSlug)
    {
        if (is_string($idOrSlug)) {
        }
        $return = [
            'content' => \App\Models\Meta::factory()->times(1)->make()->first(),
        ];
        return $this->view('meta', $return);
    }
    protected function view_content($idOrSlug)
    {
        if (is_string($idOrSlug)) {
        }
        $return = [
            'content' => \App\Models\Content::factory()->times(1)->make()->first(),
        ];

        return $this->view('content', $return);
    }
    protected function view_link($idOrSlug)
    {
        if (is_string($idOrSlug)) {
        }
        $return = [
            'content' => \App\Models\Meta::factory()->times(1)->make()->first(),
        ];
        return $this->view('link', $return);
    }
    protected function view_admin()
    {
    }

    protected function view_market()
    {
    }
    protected function getTableData($default = [])
    {
        // var_dump(__METHOD__);
        $return = [];
        // var_dump($this->models);
        // var_dump($this->moduleOption);
        foreach ($this->models ?? [] as $tableKey => $tableModel) {
            // var_dump($tableKey);
            $plural_tableKey = \Str::plural($tableKey);
            $tableTypeOptions = $this->option($tableKey . ".type", []);
            // var_dump($tableTypeOptions);
            $return[$plural_tableKey] = sizeof($tableTypeOptions) > 0
                ? array_reduce(
                    $tableTypeOptions,
                    function ($total, $item) use ($default, $tableModel, $tableKey, $plural_tableKey) {
                        if (\Arr::get($item, 'auth', false))
                            return $total;
                        $key = $item['value'];
                        $plural_key = \Str::plural($item['value']);
                        if (Auth::check()) {
                            $total[$plural_key] = \Arr::get(
                                $default,
                                $plural_tableKey . '.' . $plural_key,
                                $builder = $tableModel::where('type', $key)
                                    ->where('user', Auth::id())
                                    ->orderBy('updated_at', 'desc')
                            );
                        } else {
                            $total[$plural_key] = \Arr::get(
                                $default,
                                $plural_tableKey . '.' . $plural_key,
                                $builder = $tableModel::where('type', $key)
                                    ->whereIn('status', ['publish', 'public'])
                                    ->orderBy('updated_at', 'desc')
                            );
                        }
                        if (isset($builder)) {
                            \Arr::set($this->sqls, "select_{$tableKey}_{$key}_list", $builder->toSql());
                            $total[$plural_key] = $builder->paginate(15);
                        }
                        return $total;
                    },
                    []
                )
                : \Arr::get(
                    $default,
                    $plural_tableKey,
                    $tableModel::where('user', Auth::id() ?? 0)->orderBy('updated_at', 'desc')->paginate(15)
                );
        }
        return $return;
    }

}
