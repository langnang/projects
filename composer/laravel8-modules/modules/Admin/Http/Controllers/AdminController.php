<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Illuminate\Routing\Controller;

class AdminController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        return $this->view('index');
    }
}

