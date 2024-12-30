<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Illuminate\Routing\Controller;

class AdminLinkController extends AdminController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return $this->view('data.link-list', [
            'paginator' => $this->getLinkClass()::paginate(20),
        ]);
    }
}

