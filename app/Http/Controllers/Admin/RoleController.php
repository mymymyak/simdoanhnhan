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
use App\Repositories\User\RoleInterface;
use App\Services\Pagination;
use App\Exceptions\Validation\ValidationException;
use Sentinel;

class RoleController extends Controller {

    protected $perPage = 10;
    protected $role;

    public function __construct(RoleInterface $role) {
        $this->perPage = config('global.perpage');
        $this->role = $role;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function Index(Request $request) {
        $roles = $this->role->paginateSimple($this->perPage, false);
        if($request->ajax()) {
            $html = View::make('protected.admin.user.roles.list', array('roles' => $roles))->render();
            return Response::json(array('html' => $html));
        }
        return view('protected.admin.users.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $routeList = $this->role->getAllRoute();

        return view('protected.admin.users.roles.create', ['routeList' => $routeList]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        try {
            $this->role->create(Input::all());
            return redirect()->route('role.index');
        } catch (ValidationException $e) {
            return redirect()->route('role.create')->withInput()->withErrors($e->getErrors());
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
        $role = $this->role->find($id);

        return view('protected.admin.users.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id) {
        $role = $this->role->find($id);
        $routeList = $this->role->getAllRoute();
        return view('protected.admin.users.roles.edit', compact('role', 'routeList'));
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
            $this->role->update($id, Input::all());
            return redirect()->route('role.index');
        } catch (ValidationException $e) {
            return redirect()->route('role.edit')->withInput()->withErrors($e->getErrors());
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
        $this->role->delete($id);
        return redirect()->route('role.index');
    }

}
