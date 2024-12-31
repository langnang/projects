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
        'meta' => \App\Models\Meta::class,
        'content' => \App\Models\Content::class,
        'field' => \App\Models\Field::class,
        'link' => \App\Models\Link::class,
        'comment' => \App\Models\Comment::class,
        'user' => \App\Models\User::class,
        'option' => \App\Models\Option::class,
        'log' => \App\Models\Log::class,
        'relationship' => \App\Models\Relationship::class,
    ];
    protected $mergeModels = [];

    public function __construct()
    {
        // $this->middleware('auth');
        if (empty($this->moduleName)) {
            if (preg_match('/^Modules\\\\(\w*)\\\\Http/i', static::class, $moduleMatches)) {
                $this->moduleName = $moduleMatches[1];
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
        return $this->moduleName ? \Arr::get($this->moduleConfig, $key, config($key, $default)) : config($key, $default);
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
                'module' => $this->getModuleAttributes(),
                'options' => $this->moduleOption,
                'layout' => null,
                'view' => $view,
                'metas' => [],
                'contents' => [],
                'links' => [],
                'children' => $this->config('hasChildren') ? \App\Models\Meta::factory()->times(10)->make() : null,
                // 'layout' => "layouts.master",

            ],
            // $this->getTableData($data),
            $data
        );
        $return['$sqls'] = $this->sqls;
        if (!isset($return['view']))
            abort(403);
        if (empty($return['layout'])) {
            $return['layout'] = $this->config('view.framework', ) . '.' . $return['view'];
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
        // var_dump($return['view']);
        if (env('WEB_CONSOLE')) {
            echo "<script>window.\$app=" . json_encode($return, JSON_UNESCAPED_UNICODE) . ";</script>";
            echo "<script>console.log('window.\$app',window.\$app);</script>";
        }
        // dump($return);
        return view($return['view'], $return, $mergeData);
    }
    protected function response($data = [], $mergeData = [])
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
                'contents' => \App\Models\Content::with(['user'])->whereIn('type', ['post'])->whereIn('status', ['public', 'publish'])->orderByDesc('updated_at')->paginate(),
                'links' => \App\Models\Link::with(['user'])->whereIn('type', ['site'])->whereIn('status', ['public', 'publish'])->orderByDesc('updated_at')->limit(20)->get(),
                'categories' => \App\Models\Meta::with(['children'])->where('type', 'category')->whereIn('status', ['public', 'publish'])->get(),
                'tags' => \App\Models\Meta::where('type', 'category')->whereIn('status', ['public', 'publish'])->get(),
                'latest_contents' => \App\Models\Content::orderByDesc('updated_at')->limit(10)->get(),
                'latest_comments' => \App\Models\Comment::orderByDesc('updated_at')->limit(10)->get(),
            ],
            // $this->getTableData($data),
            $data
        );
        // dump($return);
        return response()->json($return, 200, []);
    }
    protected function getModuleAttributes()
    {
        if (empty($this->moduleName))
            return;

        $module = $this->module;
        return array_merge($this->moduleConfig ?? [], [
            'alias' => $this->moduleAlias,
            'config' => $this->moduleConfig,
            'wrapperMeta' => $this->moduleMeta,
            'lowerName' => empty($module) ? \Str::lower($this->moduleName) : $module->getLowerName(),
            'studlyName' => empty($module) ? \Str::studly($this->moduleName) : $module->getStudlyName(),
            'path' => empty($module) ? app_path() : $module->getPath(),
            'extraPath' => empty($module) ? public_path() : $module->getExtraPath('Public'),
            'enabled' => empty($module) ? true : $module->isEnabled(),
            'disabled' => empty($module) ? false : $module->isDisabled(),
            'status' => empty($module) ? 'public' : $module->IsStatus(true),
            'requires' => empty($module) ? [] : $module->getRequires(),
        ]);
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
                            // var_dump("select_{$tableKey}_{$key}_list");
                            // var_dump($builder->toRawSql());
                            \Arr::set($this->sqls, "select_{$tableKey}_{$key}_list", $builder->toRawSql());
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
            $return['latest_' . $plural_tableKey] = [];
            $return['hottest_' . $plural_tableKey] = [];
        }
        return $return;
    }
    protected function response_index()
    {
        $return = [];

        return $this->response($return);
    }

    protected function mergeRequest($values)
    {

    }

    protected function hasModule()
    {
        return !empty($this->module);
    }

    protected function getAuthStatus($key = 'global.status')
    {
        return $this->option($key);
        // return Auth::check() ? [] : [];
    }

    protected function getModel($key)
    {
        $models = array_merge($this->models, $this->mergeModels);
        if (!array_key_exists($key, $models))
            return;

        return $models[$key];
    }
}
