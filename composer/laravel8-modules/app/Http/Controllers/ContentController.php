<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContentController extends Controller
{
    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $content = $this->getModel('content')::with(['contents', 'links'])->find($id);
        if (empty($content))
            abort(404);
        $return = [
            'content' => $content,
        ];
        return $this->view('content', $return);
    }

}
