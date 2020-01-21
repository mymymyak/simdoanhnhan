<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Admin;

/**
 * Description of DomainController
 *
 * @author Do Duy Duc <ducdd6647@co-well.com.vn>
 */
use View;
use Flash;
use Input;
use Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Domain\DomainInterface;
use App\Services\Pagination;
use App\Exceptions\Validation\ValidationException;
use Sentinel;

class DomainController extends Controller {

    protected $domain;
    protected $perPage = 50;

    public function __construct(DomainInterface $domain) {
        $this->domain = $domain;
        parent::__construct();
    }

    public function index(Request $request) {
	    $user = Sentinel::getUser();
	    $domains = $this->domain->paginateByDomain($this->perPage, $user->domain, false);
        if ($request->ajax()) {
            $html = View::make('protected.admin.domain.list', ['domains' => $domains])->render();
            return Response::json(array('html' => $html));
        }

        return view('protected.admin.domain.index', ['domains' => $domains]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $template = config('global.template');
        $domain = $this->domain;
        $template[''] = 'Chọn';
        ksort($template);
        return view('protected.admin.domain.create', ['template' => $template]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        try {
            $this->domain->create(Input::all());
            return redirect()->route('domain.index');
        } catch (ValidationException $e) {
            return redirect()->route('domain.create')->withInput()->withErrors($e->getErrors());
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
        $domain = $this->domain->find($id);

        return view('protected.admin.domain.show', ['domain' => $domain]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id) {
        $domain = $this->domain->find($id);
        $config = !empty($domain->config) ? json_decode($domain->config) : [];
        $template = config('global.template');
        
        $template[''] = 'Chọn';
        ksort($template);
        return view('protected.admin.domain.edit', ['domain' => $domain, 'config' => $config, 'template' => $template]);
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
            $this->domain->update($id, Input::all());
            return redirect()->route('domain.index');
        } catch (ValidationException $e) {
            return redirect()->route('domain.edit', ['id' => $id])->withInput()->withErrors($e->getErrors());
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
        $this->domain->delete($id);
        return redirect()->route('domain.index');
    }

}
