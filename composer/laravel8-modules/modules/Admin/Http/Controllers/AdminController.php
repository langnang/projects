<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class AdminController extends \App\Illuminate\Routing\Controller
{
    protected function view($view = null, $data = [], $mergeData = [])
    {
        $return = array_merge($data, [
            'view' => $view,
            'categories' => \Arr::get(
                $data,
                'categories',
                $this->getModel('meta')::with(['children'])
                    ->where('type', 'category')
                    ->whereIn('status', \Auth::check() ? ['public', 'publish', 'protected', 'private'] : ['public', 'publish'])
                    ->where('parent', $this->getModel('meta')::where('slug', 'module:admin')->first()->id)
                    ->whereNull('deleted_at')
                    ->where('name', '!=', '')
                    ->get()
            ),
            'active_category' => $this->getModel('meta')::where('slug', \Str::replace('/', ':', request()->path()))->first(),
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
}

