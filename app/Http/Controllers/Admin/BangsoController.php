<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Admin;

/**
 * Description of BangsoController
 *
 * @author Do Duy Duc <ducdd6647@co-well.com.vn>
 */
use View;
use Flash;
use Input;
use Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Bangso\BangsoRepository;
use App\Services\Pagination;
use App\Exceptions\Validation\ValidationException;

class BangsoController extends Controller {

    protected $bangso;
    protected $perPage = 20;

    public function __construct(BangsoRepository $bangso) {
        $this->bangso = $bangso;
        parent::__construct();
    }

    public function bangSo(Request $request) {
        $bangso = $this->bangso->find();

        return view('protected.admin.bangso.index', ['bangso' => $bangso]);
    }

    public function ajaxSaveBangSo() {
        try {
            $this->bangso->save(Input::all());
            return redirect()->back();
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->getErrors());
        }
    }

    public function bangSoDanhMuc(Request $request) {
        $loaisim = config('global')['LOAISIM'];
        $loaisim2 = ['' => 'Chọn danh mục'];
		$loaisim2['homepage3mang'] = '3 Mạng hiển thị trang chủ';
		$loaisim2['simvip3mang'] = 'Sim vip 3 mạng trang chủ';
		$loaisim2['sim-gia-duoi-500-nghin'] = 'Sim Giá dưới 500 nghìn';
        $loaisim2['sim-gia-500-nghin-den-1-trieu'] = 'Sim giá 500 nghìn đến 1 triệu';
        $loaisim2['sim-gia-1-trieu-den-3-trieu'] = 'Sim giá 1 triệu đến 3 triệu';
        $loaisim2['sim-gia-3-trieu-den-5-trieu'] = 'Sim giá 3 triệu đến 5 triệu';
        $loaisim2['sim-gia-5-trieu-den-10-trieu'] = 'Sim giá 5 triệu đến 10 triệu';
        $loaisim2['sim-gia-10-trieu-den-50-trieu'] = 'Sim giá 10 triệu đến 50 triệu';
        $loaisim2['sim-gia-50-trieu-den-100-trieu'] = 'Sim giá 50 triệu đến 100 triệu';
        $loaisim2['sim-gia-100-trieu-den-200-trieu'] = 'Sim giá 100 triệu đến 200 triệu';
        $loaisim2['sim-gia-tren-200-trieu'] = 'Sim giá trên 200 triệu';
        $loaisim2['sim-viettel'] = 'Sim viettel';
        $loaisim2['sim-vinaphone'] = 'Sim vinaphone';
        $loaisim2['sim-mobifone'] = 'Sim mobifone';
        $loaisim2['sim-vietnamobile'] = 'Sim vietnamobile';
        $loaisim2['sim-gmobile'] = 'Sim gmobile';
        $loaisim2['sim-itelecom'] = 'Sim itelecom';
        foreach ($loaisim as $key => $loai) {
			if (in_array($key, [110, 111, 112,113,114,120,121,122,123,124,125])) {
				continue;
			}
            $loaisim2[khongdau($loai)] = $loai;
        }
		$loaisim2['sim-gia-re'] = 'Sim giá rẻ';

        return view('protected.admin.bangso.danhmuc', ['loaisim' => $loaisim2]);
    }
    public function bangSoDanhMucAjax(Request $request) {
        echo $this->bangso->getBangsoDetail($request->all());exit;
    }
    public function bangSoDanhMucSave(Request $request) {
        try {
            $this->bangso->saveDanhmuc(Input::all());
            return json_encode(['save' => true, 'message' => 'Lưu thành công']);
        } catch (ValidationException $e) {
            return json_encode(['save' => false, 'message' => 'Có lỗi vui lòng liên hệ ADMIN']);
        }
    }

    public function bangSoTrangchu() {
        $bangso = $this->bangso->findBangsotrangchu();
        return view('protected.admin.bangso.trangchu', ['bangso' => $bangso]);
    }

    public function bangSoTrangchuSave() {
        try {
            $this->bangso->saveBangsoTrangchu(Input::all());
            return redirect()->back()->withFlashMessage('Bảng số Successfully Created!');
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->getErrors());
        }
    }

	public function linkgoiy() {
		$listItem = $this->bangso->getLinkGoiY();
		return view('protected.admin.bangso.linkgoiy', ['listItem' => $listItem]);
	}
	public function linkgoiySave() {
		try {
            $this->bangso->saveLinkGoiY(Input::all());
            return redirect()->back()->withFlashMessage('Save Successfully!');
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->getErrors());
        }
	}

	public function sitemapAndRobot(){
		return view('protected.admin.bangso.sitemapAndRobots');
	}

	public function sitemapAndRobotDoUpload (Request $request) {
		if ($request->hasFile('sitemap')) {
			$sitemap = $request->sitemap;
			$this->bangso->uploadSitemapAndRobot($sitemap, $sitemap->getClientOriginalName());
		}
		if ($request->hasFile('robots')) {
			$robots = $request->robots;
			$this->bangso->uploadSitemapAndRobot($robots, 'robots.txt');
		}
		return redirect()->back()->withFlashMessage('Save Successfully!');
	}
}
