<?php

namespace Modules\WebPage\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Illuminate\Routing\Controller;

class WebPageController extends \App\Http\Controllers\Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $return = [
            'content' => \App\Models\Content::factory()->times(1)->make()->first()
        ];
        $return['content']->dependencies = [
            ["name" => "bootstrap", "version" => "4.6.2", "type" => "css", "cdn" => "unpkg", "file" => "/dist/css/bootstrap.min.css", "url" => "https://unpkg.com/bootstrap@4.6.2/dist/css/bootstrap.min.css"],
            ["name" => "jquery", "version" => "3.7.1", "type" => "javascript", "cdn" => "unpkg", "file" => "/dist/jquery.slim.min.js", "url" => "https://unpkg.com/jquery@3.7.1/dist/jquery.slim.min.js"],
            ["name" => "bootstrap", "version" => "4.6.2", "type" => "javascript", "cdn" => "unpkg", "file" => "/dist/js/bootstrap.bundle.min.js", "url" => "https://unpkg.com/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"],
            ["name" => "feather-icons", "version" => "4.29.2", "type" => "javascript", "cdn" => "unpkg", "file" => "/dist/feather.min.js", "url" => "https://unpkg.com/feather-icons@4.29.2/dist/feather.min.js"],
            ["name" => "chart.js", "version" => "4.4.7", "type" => "javascript", "cdn" => "unpkg", "file" => "/dist/chart.umd.js", "url" => "https://unpkg.com/chart.js@4.4.7/dist/chart.umd.js"],
        ];
        $return['content']->style = '';
        $return['content']->script = '';
        // dump($return);
        return view('webpage::empty.index', $return);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('webpage::empty.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('webpage::empty.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('webpage::empty.edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
