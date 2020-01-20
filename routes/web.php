<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | This file is where you may define all of the routes that are handled
  | by your application. Just tell Laravel the URIs it should respond
  | to using a Closure or controller method. Build something great!
  |
 */

$isSecure = false;
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
    $isSecure = true;
}
elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
    $isSecure = true;
}

if ($isSecure) {
    \URL::forceScheme('https');
}
// 301 redirect
$currentPath = request()->path();
if (!empty(config('domainInfo')['url_301'])) {
	$list301 = explode(PHP_EOL, config('domainInfo')['url_301']);
	if (!empty($list301) && is_array($list301)) {
		foreach ($list301 as $item301) {
			$item301Split = explode('|', $item301);
			if (!empty($item301Split[1])) {
				$pathOld = trim($item301Split[0]);
				if ('/' . $currentPath != trim($item301Split[1]) && startsWith($pathOld, '/'.$currentPath)) {
					header("HTTP/1.1 301 Moved Permanently");
					header("Location: ".trim($item301Split[1]));
					exit();
				}
			}
		}
	}
}
Route::get('/', ['as' => 'home', 'uses' => 'SimController@homePage']);

//Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/404', 'HomeController@notFound');

Route::get('/0{alias}.jpg', array('as' => 'frontend.sim.image', 'uses' => 'SimController@actionImageSim'))->where(['alias' => '[0-9]+']);
Route::get('/0{alias}', array('as' => 'frontend.sim.detail', 'uses' => 'SimController@actionDetailSim'))->where(['alias' => '[0-9]+']);
Route::post('/sim-order', array('as' => 'frontend.sim.order', 'uses' => 'SimController@actionOrder'));

Route::get('/sim-dep', array('as' => 'frontend.sim.customquery', 'uses' => 'SimController@actionCustomQuery'));

// PAGE
Route::get('/page/{slug}', array('as' => 'frontend.page.detail', 'uses' => 'HomeController@actionDetailPage'));

// PRICE
Route::get('/sim-gia-duoi-{maxPrice}-{dvTo}', array('as' => 'frontend.gia_duoi', 'uses' => 'SimController@actionPrice'))->where(['maxPrice' => '[0-9]+', 'dvTo' => 'nghin']);
Route::get('/sim-gia-tren-{minPrice}-{dvFrom}', array('as' => 'frontend.gia_tren', 'uses' => 'SimController@actionPrice'))->where(['minPrice' => '[0-9]+', 'dvFrom' => 'trieu']);
Route::get('/sim-gia-{minPrice}-{dvFrom}-den-{maxPrice}-{dvTo}', array('as' => 'frontend.gia_tu_den', 'uses' => 'SimController@actionPrice'))->where(['minPrice' => '[0-9]+', 'maxPrice' => '[0-9]+', 'dvFrom' => 'nghin|trieu', 'dvTo' => 'trieu']);

// NAM SINH
Route::get('/sim-nam-sinh-{alias}', ['as' => 'frontend.namsinh', 'uses' => 'SimController@actionNamsinh'])->where(['alias' => '[0-9]+']);

// TRA GOP
Route::get('/sim-tra-gop', ['as' => 'frontend.tragop', 'uses' => 'SimController@actionTragop']);

# SIM GIA RE
Route::get('/sim-gia-re', ['as' => 'frontend.giare', 'uses' => 'SimController@actionGiare']);

// TIM KIEM
Route::get('/sim-so-dep-{alias}', ['as' => 'frontend.timkiem', 'uses' => 'SimController@actionSearch']);

// LOAI SO DEP DAC BIET
Route::get('/sim-{loai}-{alias}', ['as' => 'frontend.loaisim2', 'uses' => 'SimController@actionLoaisim2'])->where(['alias' => '[0-9]+', 'loai' => 'tam-hoa|tu-quy|ngu-quy|luc-quy|tien-len|so-doc|loc-phat|ong-dia|than-tai']);

// SIM LOAI DAU SO, DUOI SO
Route::get('/sim-{loai}-{alias}-dau-{dau}', ['as' => 'frontend.loai_dau_so', 'uses' => 'SimController@actionLoaiDauSo'])
    ->where(['alias' => '[0-9]+', 'loai' => '[0-9A-Za-z\-]+', 'dau' => '[0-9]+']);

// DAU SO
Route::get('/sim-dau-so-{alias}', ['as' => 'frontend.dauso', 'uses' => 'SimController@actionDauso'])->where(['alias' => '[0-9]+']);

//DUOISO
Route::get('/sim-duoi-so-{alias}', ['as' => 'frontend.duoiso', 'uses' => 'SimController@actionDuoiso'])->where(['alias' => '[0-9]+']);

// LOAI + MANG
Route::get('/sim-{slug}', array('as' => 'frontend.loai_mang', 'uses' => 'SimController@actionLoaiMang'))->where(['slug' => '[0-9A-Za-z\-]+']);

Route::get('/bai-viet/{slug}-{id}', array('as' => 'frontend.news.detail', 'uses' => 'HomeController@actionDetailNew'))->where(['slug' => '[0-9A-Za-z\-]+'],['id' => '[0-9]']);
Route::get('/tin-tuc-sim-so', array('as' => 'frontend.news.list', 'uses' => 'HomeController@actionDetailList'));

//Sitemap và robot
Route::get('/{alias}.xml', ['as' => 'frontend.sitemap', 'uses' => 'SimController@sitemap']);
Route::get('/robots.txt', ['as' => 'frontend.robot', 'uses' => 'SimController@robots']);
# Static Pages. Redirecting admin so admin cannot access these pages.
Route::group(['middleware' => ['redirectAdmin']], function() {
    //Route::get('/', ['as' => 'home', 'uses' => 'PagesController@getHome']);
    Route::get('about', ['as' => 'about', 'uses' => 'PagesController@getAbout']);
    Route::get('contact', ['as' => 'contact', 'uses' => 'PagesController@getContact']);
});

# Registration
Route::group(['middleware' => 'guest'], function() {
    //Route::get('register', 'RegistrationController@create');
    //Route::post('register', ['as' => 'registration.store', 'uses' => 'RegistrationController@store']);
});

# Authentication
//Route::get('login', ['as' => 'login', 'middleware' => 'guest', 'uses' => 'SessionsController@create']);
Route::get('logout', ['as' => 'logout', 'uses' => 'SessionsController@destroy']);
Route::resource('sessions', 'SessionsController', ['only' => ['create', 'store', 'destroy']]);

# Forgotten Password
Route::group(['middleware' => 'guest'], function() {
    Route::get('forgot_password', 'Auth\PasswordController@getEmail');
    Route::post('forgot_password', 'Auth\PasswordController@postEmail');
    Route::get('reset_password/{token}', 'Auth\PasswordController@getReset');
    Route::post('reset_password/{token}', 'Auth\PasswordController@postReset');
});

# Standard User Routes
Route::group(['middleware' => ['auth', 'standardUser']], function() {
    Route::get('userProtected', 'StandardUser\StandardUserController@getUserProtected');
    Route::resource('profiles', 'StandardUser\UsersController', ['only' => ['show', 'edit', 'update']]);
});

# Admin Routes
Route::group(['middleware' => ['auth', 'admin']], function() {
    Route::get('admin', ['as' => 'admin_dashboard', 'uses' => 'Admin\AdminController@getHome']);
    Route::resource('admin/profiles', 'Admin\AdminUsersController', ['only' => ['index', 'show', 'edit', 'update', 'destroy', 'create', 'store']]);
    Route::post('admin/set-domain', ['as' => 'admin_set_domain', 'uses' => 'Admin\AdminController@setDomain']);
});
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    Route::get('groups', ['as' => 'managers.user.role.index', 'uses' => 'RoleController@Index'])->middleware('hasRole');
    Route::get('login', ['as' => 'admin.login', 'middleware' => 'guest', 'uses' => 'AdminController@login']);
    Route::post('login', ['as' => 'admin.login', 'middleware' => 'guest', 'uses' => 'AdminController@postLogin']);
});
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin']], function () {
    Route::resource('blog', 'BlogController');
    Route::resource('role', 'RoleController');
    Route::get('seo-config/ajax-search', ['as' => 'admin.seo.config.ajax.search', 'uses' => 'OptionsController@ajaxSearch']);
    Route::resource('seo-config', 'OptionsController');
    Route::resource('pages', 'PagesController');
    Route::post('seo-config/ajax-detail', ['as' => 'admin.seo.config.ajax.detail', 'uses' => 'OptionsController@ajaxDetail']);
    Route::get('bang-so', ['as' => 'managers.bangso.index', 'uses' => 'BangsoController@bangSo']);
    Route::post('bang-so', ['as' => 'managers.bangso.ajax.save', 'uses' => 'BangsoController@ajaxSaveBangSo']);
    Route::get('bang-so-danh-muc', ['as' => 'managers.bangso.danhmuc', 'uses' => 'BangsoController@bangSoDanhMuc']);
    Route::get('ajax-bang-so-danh-muc', ['as' => 'managers.bangso.danhmuc.ajax', 'uses' => 'BangsoController@bangSoDanhMucAjax']);
    Route::post('ajax-bang-so-danh-muc-save', ['as' => 'managers.bangso.danhmuc.save', 'uses' => 'BangsoController@bangSoDanhMucSave']);
    Route::get('bang-so-trang-chu', ['as' => 'managers.bangso.trang-chu', 'uses' => 'BangsoController@bangSoTrangchu']);
    Route::post('bang-so-trang-chu', ['as' => 'managers.bangso.trang-chu.save', 'uses' => 'BangsoController@bangSoTrangchuSave']);
    Route::resource('news', 'NewsController');
    Route::get('site-setting', ['as' => 'managers.site.setting', 'uses' => 'AdminController@siteSetting']);
    Route::post('site-setting', ['as' => 'managers.site.setting.store', 'uses' => 'AdminController@siteSettingSave']);

    Route::get('custom-query', ['as' => 'managers.site.custom.query', 'uses' => 'AdminController@siteSettingCustomQuery']);
	Route::get('custom-query-configuration', ['as' => 'managers.custom.query.configuration',
	                                          'uses' => 'AdminController@siteCustomQueryConfiguration']);
	Route::post('custom-query-configuration', ['as' => 'managers.custom.query.configuration.store',
	                                          'uses' => 'AdminController@siteCustomQueryConfigurationStore']);
    Route::post('custom-query', ['as' => 'managers.site.custom.query.store', 'uses' => 'AdminController@siteSettingCustomQueryStore']);
	Route::get('/clear-cache', ['as' => 'managers.site.clear.cache', 'uses' => 'AdminController@clearCache']);
	Route::get('/clear-bang-so', ['as' => 'managers.site.clear.bangso', 'uses' => 'AdminController@clearBangso']);
	Route::get('link-goi-y', ['as' => 'managers.linkgoiy.index', 'uses' => 'BangsoController@linkgoiy']);
	Route::get('sitemap-robots', ['as' => 'managers.sitemap.robot.index', 'uses' => 'BangsoController@sitemapAndRobot']);
	Route::post('sitemap-robots', ['as' => 'managers.sitemap.robot.doUpload', 'uses' => 'BangsoController@sitemapAndRobotDoUpload']);
    Route::post('link-goi-y', ['as' => 'managers.linkgoiy.save', 'uses' => 'BangsoController@linkgoiySave']);

    Route::resource('domain', 'DomainController')->middleware('admin');
	Route::resource('promotion', 'PromotionController')->middleware('admin');
});
