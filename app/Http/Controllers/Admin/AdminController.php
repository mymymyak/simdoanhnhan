<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Bangso\BangsoRepository;
use App\User;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests\LoginFormRequest;
use Sentinel;
use Response;
use Illuminate\Support\Facades\Artisan;

class AdminController extends Controller {

    protected $bangsoRes;

    public function __construct(BangsoRepository $bangsoRes)
    {
        $this->bangsoRes = $bangsoRes;
    }

    public function getHome() {
        return view('protected.admin.admin_dashboard');
    }

    public function login() {
        return view('protected.admin.login');
    }

    public function setDomain(Request $request) {
        setcookie('domain_setting', $request->post('domain'), time() + (86400 * 30), "/");
        return redirect()->back();
    }

    public function postLogin(LoginFormRequest $request) {
        if ($request->isMethod('post')) {
            $loginData = $request->only('email', 'password');
            try {

                if (Sentinel::authenticate($loginData, $request->has('remember'))) {
                    $this->redirectAdmin();
                }

                return redirect()->back()->withInput()->withErrorMessage('Invalid credentials provided');
            } catch (\Cartalyst\Sentinel\Checkpoints\NotActivatedException $e) {
                return redirect()->back()->withInput()->withErrorMessage('User Not Activated.');
            } catch (\Cartalyst\Sentinel\Checkpoints\ThrottlingException $e) {
                return redirect()->back()->withInput()->withErrorMessage($e->getMessage());
            }
        }
    }

    public function redirectAdmin() {
        $user = Sentinel::getUser();
        $admin = Sentinel::findRoleBySlug('superadmin');
	    $domainManager = Sentinel::findRoleBySlug('domainManager');
	    $domainSupporter = Sentinel::findRoleBySlug('domainSupporter');
	    if (!$user->inRole($admin) && !$user->inRole($domainManager) && !$user->inRole($domainSupporter)) {
		    Sentinel::logout();
		    return redirect()->route('home');
	    }
	    if ($user->inRole($domainManager) || $user->inRole($domainSupporter)) {
		    setcookie('domain_setting', $user->domain, time() + (86400 * 30), "/");
	    }
	    return redirect()->route('admin_dashboard');
    }
	public function clearCache() {
		Artisan::call('cache:clear');

		return '<div style="margin:0 auto;font-size:22px;display:inline-block;background:#bbe4d9;width:100%;text-align: center;">Xóa thành công! Chuyển về trang chủ sau <span id="countDown" style="color:red">3</span> giây<script>var count = 3;setInterval(function(){count--;
	document.getElementById(\'countDown\').innerHTML = count;
	if (count == 0) {
		window.location =\'/admin\'; 
	}},1000);</script></div>';
	}
	public function clearBangso() {
		$path = public_path() . '/' .request()->server('HTTP_HOST');
		$files = glob($path . "/*.txt");
		foreach($files as $file) {
			if (file_exists($file)) {
				unlink($file);
				echo $file.' was deleted'."<br>";
			}
		}
	}

    public function siteSetting(Request $request) {
        return view('protected.admin.setting');
    }

    public function siteSettingCustomQuery() {
        $customQuery = $this->bangsoRes->findCustomQuery();
        return view('protected.admin.custom_query', ['customQuery' => $customQuery]);
    }
    public function siteSettingCustomQueryStore(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->bangsoRes->saveCustomQuery($request->all());
        }
        return redirect()->back()->with('successes', ['OK']);
    }

    public function siteCustomQueryConfiguration(){
	    $customQueryConfiguration = $this->bangsoRes->findCustomQueryConfiguration();
	    return view('protected.admin.custom_query_configuration', ['customQueryConfiguration' => $customQueryConfiguration]);
    }

    public function siteCustomQueryConfigurationStore(Request $request){
	    if ($request->isMethod('post')) {
		    $this->bangsoRes->saveCustomQueryConfiguration($request->all());
	    }
	    return redirect()->back()->with('successes', ['OK']);
    }
}
