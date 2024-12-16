<?php

namespace App\Http\Controllers;

class Controller extends \Illuminate\Routing\Controller
{
    public function view($view = null, $data = [], $mergeData = [])
    {
        $return = array_merge([
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
            '$logs' => $this->getLogs(),
            'layout' => "layouts.master",
        ], is_array($view) ? $view : ['view' => $view], $data);
        if (env('WEB_CONSOLE')) {
            echo "<script>window.\$app=" . json_encode($return, JSON_UNESCAPED_UNICODE) . ";</script>";
            echo "<script>console.log('window.\$app',window.\$app);</script>";
        }
        return view($return['view'], $return, $mergeData);
    }
}
