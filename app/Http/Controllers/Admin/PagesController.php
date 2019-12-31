<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Admin;

/**
 * Description of PagesController
 *
 * @author Do Duy Duc <ducdd6647@co-well.com.vn>
 */
use View;
use Flash;
use Input;
use Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Pages\PagesInterface;
use App\Services\Pagination;
use App\Exceptions\Validation\ValidationException;

class PagesController extends Controller {

    protected $page;
    protected $perPage;

    public function __construct(PagesInterface $page) {
        $this->page = $page;
        $this->perPage = !empty(config('global.perpage')) ? config('global.perpage') : $this->perPage;
    }

    public function index(Request $request) {
        $pages = $this->page->paginateSimple($this->perPage, false);

        if ($request->ajax()) {
            $html = View::make('protected.admin.pages.list', ['pages' => $pages])->render();
            return Response::json(array('html' => $html));
        }

        return view('protected.admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {

        return view('protected.admin.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        try {
            $this->page->create(Input::all());
            return redirect()->route('pages.index');
        } catch (ValidationException $e) {
            return redirect()->route('pages.create')->withInput()->withErrors($e->getErrors());
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
        $page = $this->page->find($id);

        return view('protected.admin.pages.show', ['page' => $page]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id) {
        $page = $this->page->find($id);
        return view('protected.admin.pages.edit', ['page' => $page]);
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
            $this->page->update($id, Input::all());
            return redirect()->route('pages.index');
        } catch (ValidationException $e) {
            return redirect()->route('pages.edit')->withInput()->withErrors($e->getErrors());
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
        $this->page->delete($id);
        return redirect()->route('pages.index');
    }

}
