<?php

namespace Modules\WebNav\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
class WebNavController extends \App\Illuminate\Routing\Controller
{
    public function view_index($midOrSlug = null)
    {
        $return = [
            'metas' => \App\Models\Meta::with([
                'children' => function (HasMany $query) {
                    $query->where('type', 'category');
                },
                'contents'
            ])
                ->where('type', 'category')
                ->where('parent', $this->moduleMeta->mid)
                ->whereNull('deleted_at')
                ->get(),
            'contents' => \App\Models\Meta::with(['contents'])->find($this->moduleMeta->mid)->contents,
        ];
        // dump($return);
        return $this->view('index', $return);
    }
    public function crud_index($midOrSlug = null)
    {
        // var_dump(request()->all());
        switch (request()->input('_target')) {
            case "insert_meta_item":
                $meta = (new \App\Models\Meta(request()->all()));
                $meta->save();
                break;
            case "delete_meta_item":
                $meta = \App\Models\Meta::find(request()->input('mid'));
                $meta->timestamps = false;
                $meta->update(['deleted_at' => now()]);
                \App\Models\Relationship::where('meta_id', request()->input('mid'))->delete();
                break;
            case "update_meta_item":
                $meta = \App\Models\Meta::find(request()->input('mid'));
                $meta->fill(request()->all());
                $meta->save();
                break;
            case "insert_content_item":
                $content = (new \App\Models\Content(request()->all()));
                $content->save();
                $mids = request()->input('mids');
                if (is_string($mids)) {
                    $mids = explode(',', $mids);
                }
                foreach ($mids as $mid) {
                    \App\Models\Relationship::upsert([
                        "content_id" => $content->cid,
                        "meta_id" => $mid,
                    ], ['content_id', 'meta_id']);
                }
                // var_dump($mids);
                // var_dump($content);
                break;
            case "delete_content_item":
                $content = \App\Models\Content::find(request()->input('cid'));
                $content->timestamps = false;
                $content->update(['deleted_at' => now()]);
                \App\Models\Relationship::where('content_id', request()->input('cid'))->delete();
                break;
            case "update_content_item":
                $content = \App\Models\Content::find(request()->input('cid'));
                $content->fill(request()->all());
                $content->save();
                break;
            default:
                break;
        }
        return $this->view_index($midOrSlug);
    }
}
