<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Admin;

/**
 * Description of BlogController
 *
 * @author Do Duy Duc <ducdd6647@co-well.com.vn>
 */
use View;
use Flash;
use Input;
use Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Blog\BlogInterface;
use App\Services\Pagination;
use App\Exceptions\Validation\ValidationException;

class BlogController extends Controller {

    protected $blog;

    public function __construct(BlogInterface $blog) {
        $this->blog = $blog;
    }

    public function index(Request $request) {
        $pagi = $this->blog->paginate($request->get('page', 1), 5, false);
        $blogs = Pagination::makeLengthAware($pagi->items, $pagi->totalItems, 5);

        if ($request->ajax()) {
            $html = View::make('protected.admin.blogs.list', ['blogs' => $blogs])->render();
            return Response::json(array('html' => $html));
        }

        return view('protected.admin.blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {

        return view('protected.admin.blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        try {
            $this->blog->create(Input::all());
            return redirect()->route('blog.index');
        } catch (ValidationException $e) {
            return redirect()->route('blog.create')->withInput()->withErrors($e->getErrors());
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
        $blog = $this->blog->find($id);

        return view('protected.admin.blogs.show', ['blog' => $blog]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id) {
        $blog = $this->blog->find($id);
        return view('protected.admin.blogs.edit', ['blog' => $blog]);
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
            $this->blog->update($id, Input::all());
            return redirect()->route('blog.index');
        } catch (ValidationException $e) {
            return redirect()->route('blog.edit')->withInput()->withErrors($e->getErrors());
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
        $this->blog->delete($id);
        return redirect()->route('blog.index');
    }

}
