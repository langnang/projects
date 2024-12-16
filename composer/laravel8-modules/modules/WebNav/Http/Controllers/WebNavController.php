<?php

namespace Modules\WebNav\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class WebNavController extends \App\Illuminate\Routing\Controller
{
    public function view_index($midOrSlug = null)
    {
        $return = [
            'metas' => \App\Models\Meta::factory()->times(10)->make(),
            'contents' => \App\Models\Content::factory()->times(30)->make(),
        ];
        // dump($return);
        return $this->view('index', $return);
    }
}
