<?php
namespace App\Illuminate\Routing;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Module;

abstract class ModuleController extends \Illuminate\Routing\Controller
{
    /**
     * 模块 名称
     * @var string
     */
    protected $name;
    /**
     * 模块 别名
     * @var string
     */
    protected $alias;
    protected function setName($value = null, $pattern = null)
    {
        if (!empty($value))
            $this->name = \Str::studly($value);

        if (empty($pattern))
            $pattern = '/^Modules\\\\(\w*)\\\\Http/i';

        // $this->middleware('auth');
        if (empty($this->name)) {
            if (preg_match($pattern, static::class, $matches)) {
                $this->name = $matches[1];
            } else {
                $this->name = 'Home';
            }
        }
        $this->setAttribute('name', $this->name);
        $this->alias = \Str::lower($this->name);
        $this->setAttribute('alias', $this->alias);
    }
    protected $module;

    protected function setModule($name = null)
    {
        global $app;
        if (empty($name))
            $name = $this->name;
        $this->module = $module = Module::find($name) ?? new \Nwidart\Modules\Laravel\Module($app, 'Home', '');
        $this->setAttribute('module', $this->module);
        $this->setAttribute('enabled', $module->isEnabled());
        $this->setAttribute('disabled', $module->isDisabled());
        $this->setAttribute('status', $module->IsStatus(true));
    }
    /**
     * 模块 属性
     * @var array
     */
    protected $attributes = [
        'id' => null,
        'name' => '',
        'alias' => '',
        'module' => null,
        'status' => null,
        'enabled' => null,
        'disabled' => null,
        'meta' => null,
        'config' => [], // 配置
        'options' => [], // 选项
        'sqls' => [], // 数据库脚本
    ];
    protected function initAttributes()
    {
        $this->setAttributeMeta();
        $this->setAttributeConfig();
        $this->setAttributeOptions();
    }
    protected function setAttribute($key, $value)
    {
        \Arr::set($this->attributes, $key, $value);
    }

    protected function setAttributeMeta()
    {
        $meta = $this->getModel('meta')::where('type', 'module')
            ->where('slug', 'module:' . $this->alias)
            ->first();
        if (empty($meta))
            return;
        \Arr::set($this->attributes, 'meta', $meta);
        \Arr::set($this->attributes, 'id', $meta->id);
    }
    protected function setAttributeConfig(...$values)
    {
        if (empty($values)) {
            $this->setAttribute('config', array_merge(config($this->alias) ?? [], $values));
        }
    }
    protected function setAttributeOptions(...$values)
    {
        if (empty($values))
            $values = \Cache::rememberForever($this->alias . '_module.options', function () {
                // \DB::enableQueryLog();
                $builder = \App\Models\Option::where('name', 'like', 'global.%');
                foreach ($this->models ?? [] as $tableKey => $tableModel) {
                    $builder = $builder->orWhere('name', 'like', $tableKey . '.%');
                }
                if ($this->name)
                    $builder = $builder->orWhere('name', 'like', $this->alias . '.%');
                $this->setAttribute('sqls.select_option_list', $builder->toRawSql());
                return $builder->get()->toArray();
            });

        // var_dump($values);
        foreach ($values as $value) {
            if (isset($value['name']))
                $this->setAttribute('options.' . $value['name'], $value['value'], );
        }
        // var_dump($this->getOption());
    }
    protected function setAttributeSql(string $key, $values = null)
    {
        if (empty($values)) {
            $values = [];
            $queryLogs = \DB::getQueryLog();
            foreach ($queryLogs as $queryLog) {
                $queryLog['bindings'] = array_map(function ($item) {
                    return is_string($item) ? "'$item'" : $item;
                }, $queryLog['bindings']);
                $sql = \Str::replaceArray('?', $queryLog['bindings'], $queryLog['query']);
                array_push($values, $sql);
            }
        }
        \Arr::set($this->attributes, 'sqls.' . $key, $values);
        // \Cache::put($this->alias . '_module.sqls.' . $key, $values);
        \DB::flushQueryLog();
    }
    protected function getAttribute($key = null, $default = null)
    {
        if (empty($this->name))
            return;
        if (empty($key))
            return $this->attributes;

        return \Arr::get($this->attributes, $key, $default);

        $module = $this->module;
        $return = array_merge($this->attributes ?? [], [
            'name' => $this->name,
            'alias' => $this->alias,
            // 'path' => empty($module) ? app_path() : $module->getPath(),
            // 'extraPath' => empty($module) ? public_path() : $module->getExtraPath('Public'),

            // 'requires' => empty($module) ? [] : $module->getRequires(),
            // 'options' => $this->options,
        ]);
        // \Cache::forever($this->alias . '_module.attributes', $return);
        return $return;
    }
    protected function getConfig($key = null, $default = null)
    {
        if (empty($key))
            return \Arr::get($this->attributes, 'config');

        return \Arr::get($this->attributes, 'config.' . $key, config($key, $default));
    }
    protected function getOption($key = null, $default = null)
    {
        if (empty($key))
            return $this->getAttribute('options');

        return \Arr::get($this->getAttribute('options'), $key, $default);
    }
    /**
     * 当前用户
     * @var \App\Models\User
     */
    protected $user;
    /**
     * 数据库脚本
     * @var array
     */

    /**
     * 与控制器关联的模型列表
     * @var array
     */
    protected $models = [];
    /**
     * 附加的与控制器关联的模型列表
     * @var array
     */
    protected $mergeModels = [];
    protected function getModel($key = null, $default = null)
    {
        $models = array_merge($this->models, $this->mergeModels);
        if (empty($key))
            return $models;
        if (!array_key_exists($key, $models))
            return $default;

        return $models[$key];
    }
    /**
     * Summary of __construct
     * @param mixed $moduleName
     * @param mixed $pattern
     */
    public function __construct($moduleName = null, $pattern = null)
    {
        $this->setName($moduleName, $pattern);
        $this->setModule($moduleName);
        $this->initAttributes();
        // var_dump($this);
    }


    /**
     * Summary of cache
     * @param string $method
     * @param string $key
     * @param callable $callback
     * @param \DateInterval|\DateTimeInterface|int $ttl
     * @return void
     */
    // protected function cache(
    //     string $method,
    //     string $key,
    //     callable $callback = null,
    //     \DateInterval|\DateTimeInterface|int $ttl = null,
    // ) {
    //     return \Cache::{$method}($this->alias . '_module.' . $key, $ttl, $callback);
    // }

    // protected function session()
    // {
    // }
    /**
     * Summary of matchView
     * @param mixed $data
     * @return mixed
     */
    protected function matchView($data)
    {
        if (!isset($data['view']))
            abort(403);

        if (empty($data['layout'])) {
            $data['layout'] = $this->getConfig('view.framework', ) . '.' . $data['view'];
            // var_dump($return['layout']);
            if (!View::exists($data['layout'])) {
                $data['layout'] = $data['view'];
            }
            // var_dump($data['layout']);
            $data['view'] = $this->alias . '::' . $this->getConfig('view.framework') . '.' . $data['view'];
            // var_dump($return['view']);
        }
        if (!View::exists($data['view'])) {
            $data['view'] = $data['layout'];
        }
        return $data;
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
        $return = array_merge([
            '$user' => Auth::user(),
            // 'module' => $this->module,
            'module' => $this->getAttribute(),
            'view' => $view,
        ], $data);

        $return = $this->matchView($return);

        if (env('WEB_CONSOLE')) {
            echo "<script>window.\$app=" . json_encode($return, JSON_UNESCAPED_UNICODE) . ";</script>";
            echo "<script>console.log('window.\$app',window.\$app);</script>";
        }
        return view($return['view'], $return, $mergeData);
    }

}