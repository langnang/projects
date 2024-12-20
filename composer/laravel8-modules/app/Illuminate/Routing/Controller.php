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
        if (empty($moduleName = $this->moduleName))
            return;
        $this->module = $module = \Module::find($moduleName);
        if (empty($module))
            return;
        // Cache::put($moduleName, $moduleName);
        $this->moduleAlias = $module->getAlias();
        $this->moduleConfig = config($this->moduleAlias);

        $this->moduleMeta = \App\Models\Meta::where('slug', 'module:' . $this->moduleAlias)->first();

        $options = \App\Models\Option::where('name', 'like', 'global.%')
            ->orWhere('name', 'like', 'meta.%')
            ->orWhere('name', 'like', 'content.%')
            ->orWhere('name', 'like', 'link.%')
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
     * Summary of option
     * @param mixed $key
     * @param mixed $default
     * @return mixed
     */
    public function option($key, $default = null)
    {
        return $this->moduleName ? \Arr::get($this->moduleOption, $key, $default) : config($key, $default);
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
    public function view($view = null, $data = [], $mergeData = [])
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
    public function response()
    {
    }
    public function getModuleAttributes()
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

    public function view_index($idOrSlug = null)
    {
        $return = [];

        return $this->view('index', $return);
    }

    public function view_model($model, $idOrSlug = null)
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

    public function crud_model($model, $idOrSlug = null)
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
        var_dump($method);

        var_dump(request()->all());
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
    public function view_meta($idOrSlug)
    {
        if (is_string($idOrSlug)) {
        }
        $return = [
            'content' => \App\Models\Meta::factory()->times(1)->make()->first(),
        ];
        return $this->view('meta', $return);
    }
    public function view_content($idOrSlug)
    {
        if (is_string($idOrSlug)) {
        }
        $return = [
            'content' => \App\Models\Content::factory()->times(1)->make()->first(),
        ];

        return $this->view('content', $return);
    }
    public function view_link($idOrSlug)
    {
        if (is_string($idOrSlug)) {
        }
        $return = [
            'content' => \App\Models\Meta::factory()->times(1)->make()->first(),
        ];
        return $this->view('link', $return);
    }
    public function view_admin()
    {
    }

    public function view_market()
    {
    }
    public function getTableData($default = [])
    {
        $return = [];
        foreach ($this->models ?? [] as $tableKey => $tableModel) {
            $plural_tableKey = \Str::plural($tableKey);
            $tableTypeOptions = $this->option($tableKey . ".type", []);
            $return[$plural_tableKey] = sizeof($tableTypeOptions) > 0
                ? array_reduce(
                    $tableTypeOptions,
                    function ($total, $item) use ($default, $tableModel, $plural_tableKey) {
                        if (\Arr::get($item, 'auth', false))
                            return $total;
                        $key = $item['value'];
                        $plural_key = \Str::plural($item['value']);
                        if (Auth::check()) {
                            $total[$plural_key] = \Arr::get(
                                $default,
                                $plural_tableKey . '.' . $plural_key,
                                $tableModel::where('type', $key)
                                    ->where('user', Auth::id())
                                    ->orderBy('updated_at', 'desc')
                                    ->paginate(15)
                            );
                        } else {
                            $total[$plural_key] = \Arr::get(
                                $default,
                                $plural_tableKey . '.' . $plural_key,
                                $tableModel::where('type', $key)
                                    ->whereIn('status', ['publish', 'public'])
                                    ->orderBy('updated_at', 'desc')
                                    ->paginate(15)
                            );
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
