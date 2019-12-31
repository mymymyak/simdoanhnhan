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
use Illuminate\Validation\Rules\In;
use View;
use Flash;
use Input;
use Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Options\OptionsInterface;
use App\Exceptions\Validation\ValidationException;

class OptionsController extends Controller {

    protected $options;
    protected $perPage = 20;

    public function __construct(OptionsInterface $options) {
        $this->options = $options;
        parent::__construct();
    }

    public function index(Request $request) {
        $options = $this->options->paginateSimple($this->perPage, false);
        if ($request->ajax()) {
            $html = View::make('protected.admin.options.list', ['options' => $options])->render();
            return Response::json(array('html' => $html));
        }

        return view('protected.admin.options.index', ['options' => $options]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request) {
        $loaisim = config('global')['LOAISIM'];
        $loaisim2 = ['' => 'Chọn danh mục'];
        foreach ($loaisim as $key => $loai) {
            $loaisim2[khongdau($loai)] = $loai;
        }
        $loaisim2['sim-gia-duoi-500-nghin'] = 'Sim Giá dưới 500 nghìn';
        $loaisim2['sim-gia-500-nghin-den-1-trieu'] = 'Sim giá 500 nghìn đến 1 triệu';
        $loaisim2['sim-gia-1-trieu-den-3-trieu'] = 'Sim giá 1 triệu đến 3 triệu';
        $loaisim2['sim-gia-3-trieu-den-5-trieu'] = 'Sim giá 3 triệu đến 5 triệu';
        $loaisim2['sim-gia-5-trieu-den-10-trieu'] = 'Sim giá 5 triệu đến 10 triệu';
        $loaisim2['sim-gia-10-trieu-den-50-trieu'] = 'Sim giá 10 triệu đến 50 triệu';
        $loaisim2['sim-gia-50-trieu-den-100-trieu'] = 'Sim giá 50 triệu đến 100 triệu';
        $loaisim2['sim-gia-100-trieu-den-200-trieu'] = 'Sim giá 100 triệu đến 200 triệu';
        $loaisim2['sim-gia-tren-200-trieu'] = 'Sim giá trên 200 triệu';
        $loaisim2['sim-gia-re'] = 'Sim giá rẻ';
        $loaisim2['sim-viettel'] = 'Sim viettel';
        $loaisim2['sim-vinaphone'] = 'Sim vinaphone';
        $loaisim2['sim-mobifone'] = 'Sim mobifone';
        $loaisim2['sim-vietnamobile'] = 'Sim vietnamobile';
        $loaisim2['sim-gmobile'] = 'Sim gmobile';
        $loaisim2['sim-itelecom'] = 'Sim itelecom';
        $loaisim2['trang-chu'] = 'Trang chủ';
        $type = $request->get('type', '0');
        $type = $type == 0 ? 0 : 1;
        $headerText = $type == 0 ? 'Seo danh mục' : 'Seo tìm kiếm';
        return view('protected.admin.options.create', ['type' => $type, 'headerText' => $headerText, 'loaisim' => $loaisim2]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        try {
            $this->options->create(Input::all());
            return redirect()->route('seo-config.index');
        } catch (ValidationException $e) {
            return redirect()->route('seo-config.create')->withInput()->withErrors($e->getErrors());
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
        $options = $this->options->find($id);

        return view('protected.admin.options.show', ['news' => $options]);
    }
    public function ajaxDetail() {
        $optionName = Input::get('option_name');
        $options = $this->options->findByOptionName2($optionName);
        if ($options) {
            echo $options->option_value;exit;
        }
        echo json_encode([]);exit;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id) {
        $options = $this->options->find($id);
        $loaisim = config('global')['LOAISIM'];
        $loaisim2 = ['' => 'Chọn danh mục'];
        foreach ($loaisim as $key => $loai) {
            $loaisim2[khongdau($loai)] = $loai;
        }
        $loaisim2['sim-gia-duoi-500-nghin'] = 'Sim Giá dưới 500 nghìn';
        $loaisim2['sim-gia-500-nghin-den-1-trieu'] = 'Sim giá 500 nghìn đến 1 triệu';
        $loaisim2['sim-gia-1-trieu-den-3-trieu'] = 'Sim giá 1 triệu đến 3 triệu';
        $loaisim2['sim-gia-3-trieu-den-5-trieu'] = 'Sim giá 3 triệu đến 5 triệu';
        $loaisim2['sim-gia-5-trieu-den-10-trieu'] = 'Sim giá 5 triệu đến 10 triệu';
        $loaisim2['sim-gia-10-trieu-den-50-trieu'] = 'Sim giá 10 triệu đến 50 triệu';
        $loaisim2['sim-gia-50-trieu-den-100-trieu'] = 'Sim giá 50 triệu đến 100 triệu';
        $loaisim2['sim-gia-100-trieu-den-200-trieu'] = 'Sim giá 100 triệu đến 200 triệu';
        $loaisim2['sim-gia-tren-200-trieu'] = 'Sim giá trên 200 triệu';
        $loaisim2['sim-gia-re'] = 'Sim giá rẻ';
        $loaisim2['sim-viettel'] = 'Sim viettel';
        $loaisim2['sim-vinaphone'] = 'Sim vinaphone';
        $loaisim2['sim-mobifone'] = 'Sim mobifone';
        $loaisim2['sim-vietnamobile'] = 'Sim vietnamobile';
        $loaisim2['sim-gmobile'] = 'Sim gmobile';
        $loaisim2['sim-itelecom'] = 'Sim itelecom';
        $loaisim2['trang-chu'] = 'Trang chủ';

        $this->options->setDomain(session('domain_setting', null));
        $headerText = $options->type == 0 ? 'Seo danh mục' : 'Seo tìm kiếm';
        $dataJson = json_decode($options->option_value);
        return view('protected.admin.options.edit', ['dataJson' => $dataJson, 'type' => $options->type, 'headerText' => $headerText, 'loaisim' => $loaisim2, 'options' => $options]);
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
            $this->options->update($id, Input::all());
            return redirect()->route('seo-config.index');
        } catch (ValidationException $e) {
            return redirect()->route('seo-config.edit', ['id' => $id])->withInput()->withErrors($e->getErrors());
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
        $this->options->delete($id);
        return redirect()->route('seo-config.index');
    }

    public function ajaxSearch(Request $request) {
        $q = $request->get('q');
        $data = $this->options->searchData($q);
        $obj = [];
        if (!empty($data)) {
            foreach ($data as $key => $item) {
                $obj[$key] = ['text' => $item['text'], 'id' => $item['text']];
            }
        }
        return response()->json($obj);
    }

}
