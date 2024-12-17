<?php

namespace Modules\WebNav\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Database\Eloquent\Builder;
class WebNavController extends \App\Illuminate\Routing\Controller
{
    public function view_index($midOrSlug = null)
    {
        $return = [
            'metas' => \App\Models\Meta::with([
                'children' => function (\Illuminate\Database\Eloquent\Relations\HasMany $query) {
                    $query->where('type', 'category');
                }
            ])->where('type', 'category')
                ->where('parent', $this->moduleMeta->mid)->get(),
            'contents' => \App\Models\Content::factory()->times(30)->make(),
        ];
        // dump($return);
        return $this->view('index', $return);
    }

}
