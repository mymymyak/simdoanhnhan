<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Admin;

/**
 * Description of NewsController
 *
 * @author Do Duy Duc <ducdd6647@co-well.com.vn>
 */
use View;
use Flash;
use Input;
use Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\News\NewsInterface;
use App\Services\Pagination;
use App\Exceptions\Validation\ValidationException;

class NewsController extends Controller {

    protected $news;
    protected $perPage = 20;

    public function __construct(NewsInterface $news) {
        $this->news = $news;
        parent::__construct();
    }

    public function index(Request $request) {
        $news = $this->news->paginateSimple($this->perPage, false);
        if ($request->ajax()) {
            $html = View::make('protected.admin.news.list', ['news' => $news])->render();
            return Response::json(array('html' => $html));
        }

        return view('protected.admin.news.index', ['news' => $news]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {

        return view('protected.admin.news.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        try {
            $this->news->create(Input::all());
            return redirect()->route('news.index');
        } catch (ValidationException $e) {
            return redirect()->route('news.create')->withInput()->withErrors($e->getErrors());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id) {
        $news = $this->news->find($id);

        return view('protected.admin.news.show', ['news' => $news]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id) {
        $news = $this->news->find($id);
        return view('protected.admin.news.edit', ['news' => $news]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update($id) {
        try {
            $this->news->update($id, Input::all());
            return redirect()->route('news.index');
        } catch (ValidationException $e) {
            return redirect()->route('news.edit', ['id' => $id])->withInput()->withErrors($e->getErrors());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id) {
        $this->news->delete($id);
        return redirect()->route('news.index');
    }

}
