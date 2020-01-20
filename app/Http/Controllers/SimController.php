<?php

namespace App\Http\Controllers;

use App\Exceptions\Validation\ValidationException;
use App\Repositories\Bangso\BangsoRepository;
use App\Repositories\Elastic\ElasticInterface;
use App\Repositories\Options\OptionsInterface;
use App\Repositories\Orders\OrdersInterface;
use App\Services\SimFilter;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Input;
use Memcached;
use Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use View;

class SimController extends Controller
{
    protected $elastic;
    protected $currentPage;
    protected $offsets;
    protected $perPage = 50;
    protected $filter = [];
    protected $filterActive = [];
    protected $currentUrl;
    protected $filterService;
    protected $domain = null;
    protected $options;
    protected $bangso;
    protected $orders;
    protected $domainName;
    protected $template;

    public function __construct(ElasticInterface $elastic, OptionsInterface $options, BangsoRepository $bangso, OrdersInterface $orders, Request $request)
    {
        parent::__construct();
        $this->currentPage = $request->get('page',1);
        $this->offsets = ($this->currentPage - 1) * config('global.perpageSim');
        $this->perPage = !empty(config('global.perpageSim')) ? config('global.perpageSim') : $this->perPage;
        $this->elastic = $elastic;
        $this->currentUrl = $request->path();
        $this->filterService = new SimFilter($elastic);
        $this->bangso = $bangso;
        $this->orders = $orders;
        $this->filterData();
        $this->domain = $request->server('HTTP_HOST');
        $this->options = $options;
        $this->domainName = config('domainInfo')['domain_name'];
        $this->template = isset(config('domainInfo')['template']) ? 'templates.' . config('domainInfo')['template'] .'.' : 'templates.default.';
        $this->bangso->setDomain($this->domain);
        $this->middleware('doNotCacheResponse', ['only' => ['actionDetailSim']]);
    }

    public function filterData()
    {
        $LOAIMANG = config('global.LOAIMANG');
        $price_filter = isset($_GET["price_filter"]) ? $_GET["price_filter"] : "0";
        $telco_filter = isset($_GET["telco_filter"]) ? intval($_GET["telco_filter"]) : 0;
        $m10so_filter = isset($_GET["m10so_filter"]) ? $_GET["m10so_filter"] : 0;
        $giaban_filter = isset($_GET["giaban_filter"]) ? intval($_GET["giaban_filter"]) : 0;
        $search_ajax = isset($_GET["filter"]) && $_GET["filter"] == true ? true : false;
        if ($search_ajax == true && $price_filter == '' && $telco_filter == '' &&
            $giaban_filter == '' && $m10so_filter == '') {
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: /" . $this->currentUrl);
            exit();
        }
        if (!empty($m10so_filter)) {
            $this->filter['mm'] = in_array($m10so_filter, [0, '09', '08', '07', '05', '03']) ? $m10so_filter : 0;
        }
        if ($price_filter != "0" && $price_filter != '') {
            $temp = explode("_", $price_filter);
            $price_min = isset($temp[0]) ? intval($temp[0]) : 0;
            $price_max = isset($temp[1]) ? intval($temp[1]) : 0;
            $this->filter['minPrice'] = $price_min . '000000';
            $this->filter['maxPrice'] = $price_max . '000000';
        }
        if ($giaban_filter == 1) {
            $this->filter['order'] = ['giaban' => 'ASC'];
        } elseif ($giaban_filter == 2) {
            $this->filter['order'] = ['giaban' => 'DESC'];
        }

        if (isset($LOAIMANG[$telco_filter])) {
            $this->filter['telco_id'] = [$telco_filter];
        }
        $this->filter['search_ajax'] = $search_ajax;
        $this->filterActive = [
            'price_filter' => $price_filter,
            'telco_filter' => $telco_filter,
            'm10so_filter' => $m10so_filter,
            'giaban_filter' => $giaban_filter,
        ];
    }


    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function homePage(Request $request) {
        if($request->get("search") != null){
            $this->actionSearch($request->get("search"), $request);
        }
        $shortCodeItems = json_decode(config('domainInfo')['home_shortcode']);
        $filterParams   = [];
        if ($shortCodeItems != null) {
            $filterParams = $this->filterService->getHomepageFilterParamsByShortCode($shortCodeItems);
        }
        $priorityList = $this->filterService->getSimHotForHome($this->domain, $filterParams);
        $keyHot = 'listSimHot'.md5('trang-chu');
        $listSimHot = session()->get($keyHot);
        $options = $this->options->findByOptionNameAndDomain('trang-chu',$this->domain);
        $seoConfig = null;
        if (!empty($options)) {
            $seoConfig = json_decode($options->option_value);
        }

        View::composer('*', function ($view) use ($seoConfig) {
            $view->with('web_title', !empty($seoConfig->title) ? $seoConfig->title : 'Sim số đẹp giá rẻ - ' . $this->domainName);
            $view->with('web_h1', !empty($seoConfig->h1) ? $seoConfig->h1 : 'Sim số đẹp');
            $view->with('web_description', !empty($seoConfig->description) ? $seoConfig->description : 'Sim số đẹp giá rẻ - ' . $this->domainName);
            $view->with('web_head', !empty($seoConfig->head) ? $seoConfig->head : '');
            $view->with('web_foot', !empty($seoConfig->foot) ? $seoConfig->foot : '');
        });
        return view($this->template . 'home', [
            'listSimHot'        => $listSimHot,
            'widgetsContent'    => $priorityList
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    public function actionLoaiMang($slug, Request $request) {
        $alias = isset($slug) ? strtolower($slug) : "";
        if ($alias == '') {
            return redirect()->route('/');
        }
        $id_mang = check_ten_mang($alias);
        if ($id_mang) {
            return $this->mangdidong($id_mang, $slug);
        }
        return $this->category($slug);
    }
    public function mangdidong($id_mang, $slug) {
        $request = request();
        $paramsSearch = ['telco_id' => [$id_mang], 'page' => $this->currentPage, 'currentUrl' => $this->currentUrl];
        $paramsSearch = array_merge($paramsSearch, $this->filter);
        $dataSim = $this->filterService->getSimByCondition($paramsSearch);
        $total = 5000;
        if (count($dataSim) >= 50) {
            $paginatedItems = $this->generatePagination($total, $request->url());
        } else {
            $paginatedItems = $this->generatePagination(1, $request->url());
        }
        $options = $this->options->findByOptionNameAndDomain($this->currentUrl,$this->domain);
        $seoConfig = null;
        if (!empty($options)) {
            $seoConfig = json_decode($options->option_value);
        }
        View::composer('*', function ($view) use ($seoConfig, $id_mang) {
            $view->with('web_title', !empty($seoConfig->title) ? $seoConfig->title : config('global.LOAIMANG')[$id_mang]['title']);
            $view->with('web_h1', !empty($seoConfig->h1) ? $seoConfig->h1 : config('global.LOAIMANG')[$id_mang]['h1']);
            $view->with('web_description', !empty($seoConfig->title) ? $seoConfig->title : config('global.LOAIMANG')[$id_mang]['des']);
            $view->with('web_head', !empty($seoConfig->head) ? $seoConfig->head : '');
            $view->with('web_foot', !empty($seoConfig->foot) ? $seoConfig->foot : '');
        });

        return view($this->template .'sim.sim', [
            'total' => $total,
            'listSim' => $dataSim,
            'paginatedItems' => $paginatedItems,
            'currentPage' => $this->currentPage,
            'offsets' => $this->offsets,
            'filterActive' => $this->filterActive,
            'currentUrl' => $this->currentUrl,
            'hideMang' => true,
            'linkgoiy' => !empty($seoConfig->sim_goi_y) ? explode(PHP_EOL, $seoConfig->sim_goi_y) : []

        ]);
    }
    public function category($slug) {
        $alias = isset($slug) ? 'sim-' . trim($slug) : '';
        $catItem = getCatIdByAlias($alias);
        $catId = $catItem['cid'];
        $cat_name = $catItem['name'];
        $request = request();
        $paramsSearch = ['cat_ids' => $catId, 'page' => $this->currentPage, 'currentUrl' => $this->currentUrl];
        $paramsSearch = array_merge($paramsSearch, $this->filter);
        $dataSim = $this->filterService->getSimByCondition($paramsSearch);
        $total = 5000;
        if (count($dataSim) >= 50) {
            $paginatedItems = $this->generatePagination($total, $request->url());
        } else {
            $paginatedItems = $this->generatePagination(1, $request->url());
        }
        $seoConfig = null;
        $options = $this->options->findByOptionNameAndDomain($this->currentUrl,$this->domain);
        if (!empty($options)) {
            $seoConfig = json_decode($options->option_value);
        }
        View::composer('*', function ($view) use ($seoConfig, $cat_name) {
            $view->with('web_title', !empty($seoConfig->title) ? $seoConfig->title : $cat_name . ' - ' . $this->domainName);
            $view->with('web_h1', !empty($seoConfig->h1) ? $seoConfig->h1 : $cat_name);
            $view->with('web_description', !empty($seoConfig->description) ? $seoConfig->description : $cat_name . ' - ' . $this->domainName);
            $view->with('web_head', !empty($seoConfig->head) ? $seoConfig->head : '');
            $view->with('web_foot', !empty($seoConfig->foot) ? $seoConfig->foot : '');
        });
        return view($this->template .'sim.sim', [
            'total' => $total,
            'listSim' => $dataSim,
            'paginatedItems' => $paginatedItems,
            'currentPage' => $this->currentPage,
            'offsets' => $this->offsets,
            'filterActive' => $this->filterActive,
            'currentUrl' => $this->currentUrl,
            'linkgoiy' => !empty($seoConfig->sim_goi_y) ? explode(PHP_EOL, $seoConfig->sim_goi_y) : []
        ]);
    }

    public function actionPrice(Request $request) {
        $parameters = $request->route()->parameters();
        $from = isset($parameters["minPrice"]) && floatval($parameters["minPrice"]) ? $parameters["minPrice"] : 0;
        $to = isset($parameters["maxPrice"]) && floatval($parameters["maxPrice"]) ? $parameters["maxPrice"] : 0;
        $dvFrom = isset($parameters["dvFrom"]) && strtolower($parameters["dvFrom"]) ? $parameters["dvFrom"] : false;
        $dvTo = isset($parameters["dvTo"]) && strtolower($parameters["dvTo"]) ? $parameters["dvTo"] : false;
        if ($from > 1000 || $to > 1000) {
            return redirect()->home();
        }
        $minPrice = $maxPrice = 0;
        $textFrom = $textTo = '';
        if ($dvFrom) {
            if ($dvFrom == 'nghin') {
                $minPrice = $from * 1000;
                $textFrom = 'nghìn';
            } elseif ($dvFrom == 'trieu') {
                $minPrice = $from * 1000000;
                $textFrom = 'triệu';
            }
        }
        if ($dvTo) {
            if ($dvTo == 'nghin') {
                $maxPrice = $to * 1000;
                $textTo = 'nghìn';
            } elseif ($dvTo == 'trieu') {
                $maxPrice = $to * 1000000;
                $textTo = 'triệu';
            }
        }
        $paramsSearch = ['minPrice' => $minPrice, 'maxPrice' => $maxPrice, 'page' => $this->currentPage, 'currentUrl' => $this->currentUrl];
        $paramsSearch = array_merge($paramsSearch, $this->filter);
        $dataSim = $this->filterService->getSimByCondition($paramsSearch);
        $total = 5000;
        if (count($dataSim) >= 50) {
            $paginatedItems = $this->generatePagination($total, $request->url());
        } else {
            $paginatedItems = $this->generatePagination(1, $request->url());
        }
        $seoConfig = null;
        $options = $this->options->findByOptionNameAndDomain($this->currentUrl,$this->domain);
        if (!empty($options)) {
            $seoConfig = json_decode($options->option_value);
        }
        View::composer('*', function ($view) use ($seoConfig, $textTo, $textFrom, $maxPrice, $minPrice) {
            $view->with('web_title', !empty($seoConfig->title) ? $seoConfig->title : 'Sim số đẹp giá '.$minPrice.' '.$textFrom.' - ' . $maxPrice . ' ' . $textTo);
            $view->with('web_h1', !empty($seoConfig->h1) ? $seoConfig->h1 : 'Sim số đẹp giá '.$minPrice.' '.$textFrom.' - ' . $maxPrice . ' ' . $textTo);
            $view->with('web_description', !empty($seoConfig->description) ? $seoConfig->description : 'Sim số đẹp giá '.$minPrice.' '.$textFrom.' - ' . $maxPrice . ' ' . $textTo);
            $view->with('web_head', !empty($seoConfig->head) ? $seoConfig->head : '');
            $view->with('web_foot', !empty($seoConfig->foot) ? $seoConfig->foot : '');
        });
        return view($this->template . 'sim.sim', [
            'total' => $total,
            'listSim' => $dataSim,
            'paginatedItems' => $paginatedItems,
            'currentPage' => $this->currentPage,
            'offsets' => $this->offsets,
            'filterActive' => $this->filterActive,
            'currentUrl' => $this->currentUrl,
            'hidePrice' => true,
            'linkgoiy' => !empty($seoConfig->sim_goi_y) ? explode(PHP_EOL, $seoConfig->sim_goi_y) : []
            ]);
    }

    public function actionSearch($alias, Request $request) {
        $http = 'http://';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $http = 'https://';
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])
                  && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'
                  || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
            $http = 'https://';
        }
        $homeUrl = $request->path() == '/' ? $http . $request->server('HTTP_HOST')."/" : $request->url()."/";
        $searchQuery              = $alias;
        $ignoreViolatedCase       = preg_replace("/[^0-9^*^x]/", "", $searchQuery);
        $ignoreViolatedCase       = preg_replace('/\*+/', '*', $ignoreViolatedCase);
        $ignoreNonNumericCase     = preg_replace("/[^0-9]/", "", $searchQuery);
        $firstTwoLetters          = substr($ignoreViolatedCase, 0, 2);
        $sqlQueryable             = str_replace('x', '_',
            str_replace('*', '%', $ignoreViolatedCase));
        $numberOfMeaningCharacter = strlen($ignoreViolatedCase)
                                    - substr_count($ignoreViolatedCase, '*')
                                    - substr_count($ignoreViolatedCase, 'x');
        $explodedQuery            = explode('*', $ignoreViolatedCase);
        switch (true) {
            case $this->undefinedQuery($ignoreViolatedCase, $numberOfMeaningCharacter):
                $this->redirect(redirect()->home());
                break;
            case $this->isSpecificPhoneNumber($firstTwoLetters, $sqlQueryable, $ignoreViolatedCase, $numberOfMeaningCharacter):
                $this->redirect($homeUrl . $ignoreNonNumericCase);
                break;
            case $this->isBirthDateType($searchQuery, $ignoreNonNumericCase);
                $this->redirect($homeUrl . "sim-nam-sinh-$ignoreNonNumericCase");
                break;
            case  $this->headNumberType($explodedQuery, $firstTwoLetters):
                /** compare first number with 0 if equal then add 0 before first number */
                $headNumber = $explodedQuery[0][0] != '0' ? '0' . $explodedQuery[0] : $explodedQuery[0];
                $simUrlWithCurrentType                                                   = "";
                foreach ($this->getListSimTypesWithUrl() as $simTypeWithUrl) {
                    if (in_array((string) $explodedQuery[1], $simTypeWithUrl['data'], true)) {
                        $simUrlWithCurrentType = $simTypeWithUrl['url'];
                        break;
                    }
                }
                if (in_array($explodedQuery[1], $this->getListAcceptanceBirthYear())) {
                    $simUrlWithCurrentType = 'sim-nam-sinh';
                }
                if ($simUrlWithCurrentType == '') {
                    $simUrlWithCurrentType = 'sim-duoi';
                }
                $this->redirect($homeUrl . $simUrlWithCurrentType . "-" . $explodedQuery[1] . '-dau-' . $headNumber);
                break;
            case $this->vipNumberType($this->getListSimTypesWithUrl(), $searchQuery):
                $simTypeWithUrl = $this->getSimTypeBySearchQuery($this->getListSimTypesWithUrl(), $searchQuery);
                $this->redirect($homeUrl . $simTypeWithUrl['url'] . "-$ignoreNonNumericCase");
                break;
            case $this->manualCheckHeadNumber($searchQuery,$ignoreViolatedCase,$ignoreNonNumericCase):
                    $this->redirect($homeUrl . 'sim-dau-so' . "-$ignoreNonNumericCase");
                break;
            default:
                /** Tail number */
                $this->redirect($homeUrl . 'sim-duoi-so' . "-$ignoreNonNumericCase");
                break;
        }
    }

    /**
     * @param $ignoreViolatedCase
     * @param $countMeaningCharacter
     *
     * @return bool
     */
    private function undefinedQuery ($ignoreViolatedCase, $countMeaningCharacter) {
        if (($ignoreViolatedCase == "") || ($countMeaningCharacter <= 1)) {
            return true;
        }
        return false;
    }

    /**
     * @param $firstTwoLetter
     * @param $sqlQueryable
     * @param $ignoreViolatedCase
     * @param $numberOfMeaningCharacter
     *
     * @return bool
     * số sim cụ thể
     */
    private function isSpecificPhoneNumber ($firstTwoLetter, $sqlQueryable, $ignoreViolatedCase, $numberOfMeaningCharacter) {
        if (in_array($firstTwoLetter, [
                '01',
                '03',
                '05',
                '07',
                '08',
                '09',
            ]) && ($sqlQueryable == $ignoreViolatedCase) && ($numberOfMeaningCharacter >= 10)) {
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function getListAcceptanceBirthYear(){
        $acceptanceYears = array();
        for ($i = 1970; $i <= date('Y'); $i ++) {
            $acceptanceYears[] = $i;
        }
        return $acceptanceYears;
    }
    /**
     * @param $searchQuery
     * @param $ignoreNonNumericCase
     *
     * @return bool
     */
    private function isBirthDateType ($searchQuery, $ignoreNonNumericCase) {
        if (in_array($searchQuery, $this->getListAcceptanceBirthYear())
            || in_array($ignoreNonNumericCase, $this->getListAcceptanceBirthYear())) {
            return true;
        }
        return false;
    }

    /**
     * @param $explodedQuery
     * @param $firstTwoLetters
     *
     * @return bool
     */
    public function headNumberType ($explodedQuery, $firstTwoLetters) {
        if (count($explodedQuery) < 3) {
            if (!empty($explodedQuery[1]) && (isset($explodedQuery[0]) && $explodedQuery[0] != "")) {
                $headNumber = $explodedQuery[0][0] != '0' ? '0' . $explodedQuery[0] : $explodedQuery[0];
                if (strpos(config('global.LIST_DAUSO'), $headNumber) !== false) {
                    return true;
                }
                if (strpos('09,08,07,05,03,02', $firstTwoLetters) !== false) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param $searchQuery
     * @param $ignoreViolatedCase
     * @param $ignoreNonNumericCase
     *
     * @return bool
     */
    public function manualCheckHeadNumber ($searchQuery, $ignoreViolatedCase, $ignoreNonNumericCase) {
        // Case đầu số check thủ công
        if ($ignoreViolatedCase[0] == '0' && $ignoreViolatedCase[0] != "*"
            && (strpos(config('global.LIST_DAUSO'), $ignoreNonNumericCase) !== false
                || strpos(config('global.LIST_DAUSO'), substr($searchQuery, 0, 4)) !== false
                || strpos(config('global.LIST_DAUSO'), substr($searchQuery, 0, 3)) !== false)) {
            return true;
        }
        return false;
    }

    public function vipNumberType($searchQuery, $ignoreNonNumericCase){
        return !empty($this->getSimTypeBySearchQuery($searchQuery, $ignoreNonNumericCase));
    }

    public function getSimTypeBySearchQuery($searchQuery, $ignoreNonNumericCase){
        foreach ($this->getListSimTypesWithUrl() as $simTypeWithUrl) {
            if (in_array($searchQuery, $simTypeWithUrl['data'], true)
                || ($searchQuery[0] == '*' && in_array($ignoreNonNumericCase, $simTypeWithUrl['data']))) {
                return $simTypeWithUrl;
            }
        }
        return [];
    }
    /**
     * @return array
     */
    private function getListSimTypesWithUrl () {
        return [
            ['url'  => 'sim-tam-hoa',
             'data' => [
                 '000',
                 '111',
                 '222',
                 '333',
                 '444',
                 '555',
                 '666',
                 '777',
                 '888',
                 '999',
             ],
            ],
            [
                'url'  => 'sim-tu-quy',
                'data' => [
                    '0000',
                    '1111',
                    '2222',
                    '3333',
                    '4444',
                    '5555',
                    '6666',
                    '7777',
                    '8888',
                    '9999',
                ],
            ],
            [
                'url'  => 'sim-ngu-quy',
                'data' => [
                    '00000',
                    '11111',
                    '22222',
                    '33333',
                    '44444',
                    '55555',
                    '66666',
                    '77777',
                    '88888',
                    '99999',
                ],
            ],
            [
                'url'  => 'sim-luc-quy',
                'data' => [
                    '000000',
                    '111111',
                    '222222',
                    '333333',
                    '444444',
                    '555555',
                    '666666',
                    '777777',
                    '888888',
                    '999999',
                ],
            ],
            [
                'url'  => 'sim-tien-len',
                'data' => [
                    '0123',
                    '1234',
                    '2345',
                    '3456',
                    '4567',
                    '5678',
                    '6789',
                    '0246',
                    '1357',
                    '3579',
                    '2468',
                ],
            ],
            [
                'url'  => 'sim-so-doc',
                'data' => [
                    '1102',
                    '4953',
                    '569',
                    '9988',
                ],
            ],
            [
                'url'  => 'sim-loc-phat',
                'data' => [
                    '1368',
                    '1468',
                    '1486',
                    '68',
                    '6868',
                    '6886',
                    '86',
                    '8686',
                ],
            ],
            [
                'url'  => 'sim-ong-dia',
                'data' => [
                    '38',
                    '3838',
                    '78',
                    '7878',
                    '3878',
                ],
            ],
            [
                'url'  => 'sim-than-tai',
                'data' => [
                    '39',
                    '3939',
                    '3979',
                    '79',
                    '7979',
                ],
            ],
        ];
    }

    /**
     * @param $toUrl
     */
    private function redirect ($toUrl) {
        if (config('domainInfo')['template'] == 'amp') {
            header("AMP-Redirect-To: " . $toUrl);
            header("Access-Control-Expose-Headers: AMP-Redirect-To, AMP-Access-Control-Allow-Source-Origin");
            exit;
        }
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $toUrl);
        exit;
    }

    public function actionDauso($alias, Request $request)
    {
        if (empty($alias)) {
            return redirect()->home();
        }
        $alias = $alias[0] != '0' ? '0'.$alias : $alias;
        $mang = checkmang($alias);
        $mang = $mang == 'khongxacdinh' ? '' : $mang;
        $paramsSearch = ['dauso' => $alias, 'page' => $this->currentPage, 'currentUrl' => $this->currentUrl];
        $paramsSearch = array_merge($paramsSearch, $this->filter);
        $dataSim = $this->filterService->getSimByCondition($paramsSearch);
        $total = 5000;
        if (count($dataSim) >= 50) {
            $paginatedItems = $this->generatePagination($total, $request->url());
        } else {
            $paginatedItems = $this->generatePagination(1, $request->url());
        }

        $seoConfig = null;
        $options = $this->options->findByOptionNameAndDomain($alias."*",$this->domain);
        if (!empty($options)) {
            $seoConfig = json_decode($options->option_value);
        }
        View::composer('*', function ($view) use ($seoConfig, $mang, $alias) {
            $view->with('web_title', !empty($seoConfig->title) ? $seoConfig->title : '#1 Sim Đầu Số '.$alias.' - Sim Số Đẹp '.$mang.' đầu số '.$alias);
            $view->with('web_h1', !empty($seoConfig->h1) ? $seoConfig->h1 : 'Sim '.$mang.' '.$alias);
            $view->with('web_description', !empty($seoConfig->description) ? $seoConfig->description : 'Sim Số Đẹp '.$alias.' là đầu số đẹp của nhà mạng '.$mang.'. Mua sim đầu số '.$alias.' giá rẻ ngay tại '.$this->domain);
            $view->with('web_head', !empty($seoConfig->head) ? $seoConfig->head : '');
            $view->with('web_foot', !empty($seoConfig->foot) ? $seoConfig->foot : '');
        });
        return view($this->template .'sim.sim', [
            'total' => $total,
            'listSim' => $dataSim,
            'paginatedItems' => $paginatedItems,
            'currentPage' => $this->currentPage,
            'offsets' => $this->offsets,
            'filterActive' => $this->filterActive,
            'currentUrl' => $this->currentUrl,
            'hideMang' => true,
            'tag' => $alias,
            'linkgoiy' => !empty($seoConfig->sim_goi_y) ? explode(PHP_EOL, $seoConfig->sim_goi_y) : []
        ]);
    }
    public function actionDuoiso($alias, Request $request)
    {
        if (empty($alias)) {
            return redirect()->home();
        }
        $paramsSearch = ['duoiso' => $alias, 'page' => $this->currentPage, 'currentUrl' => $this->currentUrl];
        $paramsSearch = array_merge($paramsSearch, $this->filter);
        $dataSim = $this->filterService->getSimByCondition($paramsSearch);
        $total = 5000;
        if (count($dataSim) >= 50) {
            $paginatedItems = $this->generatePagination($total, $request->url());
        } else {
            $paginatedItems = $this->generatePagination(1, $request->url());
        }
        $seoConfig = null;
        $options = $this->options->findByOptionNameAndDomain('*'.$alias,$this->domain);
        if (!empty($options)) {
            $seoConfig = json_decode($options->option_value);
        }
        View::composer('*', function ($view) use ($seoConfig, $alias) {
            $view->with('web_title', !empty($seoConfig->title) ? $seoConfig->title : 'Sim số đẹp đuôi ' . $alias . ' - Sim đuôi ' . $alias . ' - '.$this->domainName);
            $view->with('web_h1', !empty($seoConfig->h1) ? $seoConfig->h1 : 'Sim số đẹp đuôi ' . $alias);
            $view->with('web_description', !empty($seoConfig->description) ? $seoConfig->description : 'Sim số đẹp đuôi ' . $alias . ' - Mua sim đuôi ' . $alias . ' tại '.$this->domain.' với giá rẻ');
            $view->with('web_head', !empty($seoConfig->head) ? $seoConfig->head : '');
            $view->with('web_foot', !empty($seoConfig->foot) ? $seoConfig->foot : '');
        });
        $linkgoiyGlobal = $this->bangso->getLinkGoiY();
        $linkgoiyGlobal = explode(PHP_EOL, $linkgoiyGlobal);
        return view($this->template . 'sim.sim', [
            'total' => $total,
            'listSim' => $dataSim,
            'paginatedItems' => $paginatedItems,
            'currentPage' => $this->currentPage,
            'offsets' => $this->offsets,
            'filterActive' => $this->filterActive,
            'currentUrl' => $this->currentUrl,
            'tag' => $alias,
            'linkgoiy' => !empty($seoConfig->sim_goi_y) ? explode(PHP_EOL, $seoConfig->sim_goi_y) : $linkgoiyGlobal
        ]);
    }

    public function actionLoaisim2($loai, $alias, Request $request) {
        if (empty($alias) || empty($loai)) {
            return redirect()->home();
        }
        $paramsSearch = ['duoiso' => $alias, 'page' => $this->currentPage, 'currentUrl' => $this->currentUrl];
        $paramsSearch = array_merge($paramsSearch, $this->filter);
        $dataSim = $this->filterService->getSimByCondition($paramsSearch);
        $total = 5000;
        if (count($dataSim) >= 50) {
            $paginatedItems = $this->generatePagination($total, $request->url());
        } else {
            $paginatedItems = $this->generatePagination(1, $request->url());
        }
        switch ($loai) {
            case 'tam-hoa':
                $title = 'Sim tam hoa ' . $alias;
                $w_title = 'Sim tam hoa ' . $alias . ' - Mua Sim Đuôi ' . $alias;
                $w_description = 'Sim tam hoa ' . $alias . ' - Kho sim đuôi ' . $alias . ' giá rẻ tại '.$this->domainName.'. Mua Ngay';
                break;
            case 'tu-quy':
                $title = 'Sim tứ quý ' . $alias;
                $w_title = 'Sim tứ quý ' . $alias . ' - Mua Sim Đuôi ' . $alias;
                $w_description = 'Sim tứ quý ' . $alias . ' - Kho sim đuôi ' . $alias . ' giá rẻ tại '.$this->domainName.'. Mua Ngay';
                break;
            case 'ngu-quy':
                $title = 'Sim ngũ quý ' . $alias;
                $w_title = 'Sim ngũ quý ' . $alias . ' - Mua Sim Đuôi ' . $alias;
                $w_description = 'Sim ngũ quý ' . $alias . ' - Kho sim đuôi ' . $alias . ' giá rẻ tại '.$this->domainName.'. Mua Ngay';
                break;
            case 'luc-quy':
                $title = 'Sim lục quý ' . $alias;
                $w_title = 'Sim lục quý ' . $alias . ' - Mua Sim Đuôi ' . $alias;
                $w_description = 'Sim lục quý ' . $alias . ' - Kho sim đuôi ' . $alias . ' giá rẻ tại '.$this->domainName.'. Mua Ngay';
                break;
            case 'tien-len':
                $title = 'Sim tiến lên ' . $alias;
                $w_title = 'Sim tiến lên ' . $alias . ' - Mua Sim Đuôi ' . $alias;
                $w_description = 'Sim tiến lên ' . $alias . ' - Kho sim đuôi ' . $alias . ' giá rẻ tại '.$this->domainName.'. Mua Ngay';
                break;
            case 'so-doc':
                $title = 'Sim số độc ' . $alias;
                $w_title = 'Sim số độc ' . $alias . ' - Mua Sim Đuôi ' . $alias;
                $w_description = 'Sim số độc ' . $alias . ' - Kho sim đuôi ' . $alias . ' giá rẻ tại '.$this->domainName.'. Mua Ngay';
                break;
            case 'loc-phat':
                $title = 'Sim lộc phát ' . $alias;
                $w_title = 'Sim lộc phát ' . $alias . ' - Mua Sim Đuôi ' . $alias;
                $w_description = 'Sim lộc phát ' . $alias . ' - Kho sim đuôi ' . $alias . ' giá rẻ tại '.$this->domainName.'. Mua Ngay';
                break;
            case 'ong-dia':
                $title = 'Sim ông địa ' . $alias;
                $w_title = 'Sim ông địa ' . $alias . ' - Mua Sim Đuôi ' . $alias;
                $w_description = 'Sim ông địa ' . $alias . ' - Kho sim đuôi ' . $alias . ' giá rẻ tại '.$this->domainName.'. Mua Ngay';
                break;
            case 'than-tai':
                $title = 'Sim thần tài ' . $alias;
                $w_title = 'Sim thần tài ' . $alias . ' - Mua Sim Đuôi ' . $alias;
                $w_description = 'Sim thần tài ' . $alias . ' - Kho sim đuôi ' . $alias . ' giá rẻ tại '.$this->domainName.'. Mua Ngay';
                break;
            default:
                $title = $w_title = $w_description = 'Sim số đẹp ' . $alias;
        }

        $seoConfig = null;
        $options = $this->options->findByOptionNameAndDomain('*'.$alias,$this->domain);
        if (!empty($options)) {
            $seoConfig = json_decode($options->option_value);
        }
        View::composer('*', function ($view) use ($seoConfig, $title, $w_title, $w_description) {
            $view->with('web_title', !empty($seoConfig->title) ? $seoConfig->title : $w_title);
            $view->with('web_h1', !empty($seoConfig->h1) ? $seoConfig->h1 : $title);
            $view->with('web_description', !empty($seoConfig->description) ? $seoConfig->description : $w_description);
            $view->with('web_head', !empty($seoConfig->head) ? $seoConfig->head : '');
            $view->with('web_foot', !empty($seoConfig->foot) ? $seoConfig->foot : '');
        });

        return view($this->template . 'sim.sim', [
            'total' => $total,
            'listSim' => $dataSim,
            'paginatedItems' => $paginatedItems,
            'currentPage' => $this->currentPage,
            'offsets' => $this->offsets,
            'filterActive' => $this->filterActive,
            'currentUrl' => $this->currentUrl,
            'hidePrice' => true,
            'tag' => $alias,
            'linkgoiy' => !empty($seoConfig->sim_goi_y) ? explode(PHP_EOL, $seoConfig->sim_goi_y) : []
        ]);
    }

    public function actionLoaiDauSo($loai, $alias, $dau, Request $request) {
        if (!$loai || !$alias || !$dau) {
            return redirect()->home();
        }
        $strLoai = '';
        switch ($loai) {
            case 'tam-hoa':
                $strLoai = 'Sim tam hoa';
                break;
            case 'tu-quy':
                $strLoai = 'Sim tứ quý';
                break;
            case 'ngu-quy':
                $strLoai = 'Sim ngũ quý';
                break;
            case 'luc-quy':
                $strLoai = 'Sim lục quý';
                break;
            case 'nam-sinh':
                $strLoai = 'Sim năm sinh';
                break;
            case 'tien-len':
                $strLoai = 'Sim tiến lên';
                break;
            case 'so-doc':
                $strLoai = 'Sim số độc';
                break;
            case 'loc-phat':
                $strLoai = 'Sim lộc phát';
                break;
            case 'ong-dia':
                $strLoai = 'Sim ông địa';
                break;
            case 'than-tai':
                $strLoai = 'Sim thần tài';
                break;
            default:
                $loai = 'duoi';
                $strLoai = 'Sim số đẹp';
        }
        $title = $strLoai . ' ' . $alias;
        $w_title = $strLoai . ' ' . $alias . ' - Mua Sim Đuôi ' . $alias;
        $w_description = $strLoai . ' ' . $alias . ' - Kho sim đuôi ' . $alias . ' giá rẻ tại '.$this->domainName.'. Mua Ngay';

        $seoConfig = null;
        $options = $this->options->findByOptionNameAndDomain($this->currentUrl,$this->domain);
        if (!empty($options)) {
            $seoConfig = json_decode($options->option_value);
        }
        View::composer('*', function ($view) use ($seoConfig, $title, $w_title, $w_description) {
            $view->with('web_title', !empty($seoConfig->title) ? $seoConfig->title : $w_title);
            $view->with('web_h1', !empty($seoConfig->h1) ? $seoConfig->h1 : $title);
            $view->with('web_description', !empty($seoConfig->description) ? $seoConfig->description : $w_description);
            $view->with('web_head', !empty($seoConfig->head) ? $seoConfig->head : '');
            $view->with('web_foot', !empty($seoConfig->foot) ? $seoConfig->foot : '');
        });

        $paramsSearch = ['loaidauso' => true,'dauso' => $dau, 'duoiso' => $alias, 'loaiData' => [$dau, $alias], 'page' => $this->currentPage, 'currentUrl' => $this->currentUrl];
        $paramsSearch = array_merge($paramsSearch, $this->filter);
        $dataSim = $this->filterService->getSimByCondition($paramsSearch);
        $total = 5000;
        if (count($dataSim) >= 50) {
            $paginatedItems = $this->generatePagination($total, $request->url());
        } else {
            $paginatedItems = $this->generatePagination(1, $request->url());
        }

        return view($this->template . 'sim.sim', [
            'total' => $total,
            'listSim' => $dataSim,
            'paginatedItems' => $paginatedItems,
            'currentPage' => $this->currentPage,
            'offsets' => $this->offsets,
            'filterActive' => $this->filterActive,
            'currentUrl' => $this->currentUrl,
            'tag' => $dau .'*'. $alias,
            'linkgoiy' => !empty($seoConfig->sim_goi_y) ? explode(PHP_EOL, $seoConfig->sim_goi_y) : []
        ]);

    }

    public function actionNamsinh($alias, Request $request) {
        if (empty($alias)) {
            return redirect()->home();
        }

        $array = range(1970, 2025);
        if (!in_array($alias, $array)) {
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: " . url('/') .'/' . "sim-duoi-so-$alias");
            exit();
        }
        $paramsSearch = ['yid' => $alias, 'duoiso' => $alias, 'page' => $this->currentPage, 'currentUrl' => $this->currentUrl];
        var_dump($this->currentUrl);die();
        $paramsSearch = array_merge($paramsSearch, $this->filter);
        $dataSim = $this->filterService->getSimByCondition($paramsSearch);
        $total = 5000;
        if (count($dataSim) >= 50) {
            $paginatedItems = $this->generatePagination($total, $request->url());
        } else {
            $paginatedItems = $this->generatePagination(1, $request->url());
        }

        $seoConfig = null;
        $options = $this->options->findByOptionNameAndDomain('*'.$alias,$this->domain);
        if (!empty($options)) {
            $seoConfig = json_decode($options->option_value);
        }
        View::composer('*', function ($view) use ($seoConfig, $alias) {
            $view->with('web_title', !empty($seoConfig->title) ? $seoConfig->title : 'Sim năm sinh ' . $alias . ' - Mua Sim Đuôi ' . $alias);
            $view->with('web_h1', !empty($seoConfig->h1) ? $seoConfig->h1 : 'Sim năm sinh ' . $alias);
            $view->with('web_description', !empty($seoConfig->description) ? $seoConfig->description : 'Sim năm sinh ' . $alias . ' - Kho sim đuôi ' . $alias . ' giá rẻ tại '.$this->domain.'. Mua Ngay');
            $view->with('web_head', !empty($seoConfig->head) ? $seoConfig->head : '');
            $view->with('web_foot', !empty($seoConfig->foot) ? $seoConfig->foot : '');
        });

        return view($this->template .'sim.sim', [
            'total' => $total,
            'listSim' => $dataSim,
            'paginatedItems' => $paginatedItems,
            'currentPage' => $this->currentPage,
            'offsets' => $this->offsets,
            'filterActive' => $this->filterActive,
            'currentUrl' => $this->currentUrl,
            'tag' => $alias,
            'linkgoiy' => !empty($seoConfig->sim_goi_y) ? explode(PHP_EOL, $seoConfig->sim_goi_y) : []
        ]);
    }

    public function actionTragop(Request $request) {
        $paramsSearch = ['simtragop' => true, 'page' => $this->currentPage, 'currentUrl' => $this->currentUrl];
        $paramsSearch = array_merge($paramsSearch, $this->filter);
        $dataSim = $this->filterService->getSimByConditionTrasauKM($paramsSearch);
        $total = 5000;
        if ($dataSim != null && count($dataSim) >= 50) {
            $paginatedItems = $this->generatePagination($total, $request->url());
        } else {
            $paginatedItems = $this->generatePagination(1, $request->url());
        }

        $seoConfig = null;
        $options = $this->options->findByOptionNameAndDomain($this->currentUrl,$this->domain);
        if (!empty($options)) {
            $seoConfig = json_decode($options->option_value);
        }
        View::composer('*', function ($view) use ($seoConfig) {
            $view->with('web_title', !empty($seoConfig->title) ? $seoConfig->title : 'Sim trả góp');
            $view->with('web_h1', !empty($seoConfig->h1) ? $seoConfig->h1 : 'Sim trả góp');
            $view->with('web_description', !empty($seoConfig->description) ? $seoConfig->description : 'Sim trả góp giá rẻ tại '.$this->domainName.'. Mua Ngay');
            $view->with('web_head', !empty($seoConfig->head) ? $seoConfig->head : '');
            $view->with('web_foot', !empty($seoConfig->foot) ? $seoConfig->foot : '');
        });

        return view($this->template . 'sim.sim', [
            'total' => $total,
            'listSim' => $dataSim,
            'paginatedItems' => $paginatedItems,
            'currentPage' => $this->currentPage,
            'offsets' => $this->offsets,
            'filterActive' => $this->filterActive,
            'currentUrl' => $this->currentUrl,
            'linkgoiy' => !empty($seoConfig->sim_goi_y) ? explode(PHP_EOL, $seoConfig->sim_goi_y) : []
        ]);
    }

    public function actionGiare(Request $request) {
        $paramsSearch = ['page' => $this->currentPage, 'currentUrl' => $this->currentUrl];
        $paramsSearch = array_merge($paramsSearch, $this->filter);
        $dataSim = $this->filterService->getSimByConditionTrasauKM($paramsSearch);
        $total = 5000;
        if (count($dataSim) >= 50) {
            $paginatedItems = $this->generatePagination($total, $request->url());
        } else {
            $paginatedItems = $this->generatePagination(1, $request->url());
        }

        $seoConfig = null;
        $options = $this->options->findByOptionNameAndDomain($this->currentUrl,$this->domain);
        if (!empty($options)) {
            $seoConfig = json_decode($options->option_value);
        }
        //var_dump($seoConfig);
        View::composer('*', function ($view) use ($seoConfig) {
            $view->with('web_title', !empty($seoConfig->title) ? $seoConfig->title : 'Sim giá rẻ');
            $view->with('web_h1', !empty($seoConfig->h1) ? $seoConfig->h1 : 'Sim giá rẻ');
            $view->with('web_description', !empty($seoConfig->description) ? $seoConfig->description : 'Sim giá rẻ tại '.$this->domainName.'. Mua Ngay');
            $view->with('web_head', !empty($seoConfig->head) ? $seoConfig->head : '');
            $view->with('web_foot', !empty($seoConfig->foot) ? $seoConfig->foot : '');
        });

        return view($this->template . 'sim.sim', [
            'total' => $total,
            'listSim' => $dataSim,
            'paginatedItems' => $paginatedItems,
            'currentPage' => $this->currentPage,
            'offsets' => $this->offsets,
            'filterActive' => $this->filterActive,
            'currentUrl' => $this->currentUrl,
            'linkgoiy' => !empty($seoConfig->sim_goi_y) ? explode(PHP_EOL, $seoConfig->sim_goi_y) : []
        ]);
    }

    public function actionDetailSim($alias, Request $request) {
        $sosim = '0'.$alias;
        $data = $this->bangso->getBangsoTong($this->domain);
        $simInfo = searchArray($data, 'sim', $sosim);
// var_dump($simInfo);
        if (!empty($simInfo)) {
            $obj = [
                'i' => $simInfo[0]['sim'],
                'f' => $simInfo[0]['simfull'],
                'd' => false,
                'pn' => $simInfo[0]['price'],
                'c2' => $simInfo[0]['cat_id'],
            ];
            $simInfo = json_encode($obj);
        } else {
            $simInfo = '';
            // if(class_exists('Memcached')){
                // $m = new Memcached();
                // $m->addServer('192.168.1.185', 11211);
                // $s_order = md5(rand(1000, 9999));
                // $simInfo = $m->get($sosim);
            // }
            if (empty($simInfo)) {
                $paramsSearch = ['keyword' => $alias, 'rmS3' => true, 'simSid' => []];
                $dataSim = $this->elastic->search($paramsSearch);

                if (!empty($dataSim['list'])) {
                    $line = (object)$dataSim['list'][0];
                    $simInfo2 = [
                        'i' => $line->sim,
                        'f' => $line->simfull,
                        'd' => $line->price > 0 ? false : true,
                        'pn' => $line->price,
                        'c2' => $line->cat_id,
                         'd2' => $line->d2,
                    ];
                    if(isset($line->d)) $simInfo2['d'] = $line->d;
                    if(isset($line->d2)) $simInfo2['d2'] = $line->d2;
                    if(isset($line->h)) $simInfo2['h'] = $line->h;
                    $simInfo = json_encode($simInfo2);
                    // var_dump($line);
                }
            }
        }
        // var_dump($simInfo);
        $daban = false;
        $suggest = [];
        if (!empty($simInfo)) {
            $line = json_decode($simInfo);
            if (!isset($line->f)) {
                $line->f = $line->i;
            }
            $sosim = $line->i;
            $mang = checkmang($sosim);
            $mangimg = khongdau($mang);
            $idloai = $line->c2;
            $gia2 = 0;
            if ($line->d == true || (isset($line->d2) && $line->d2 == true) ) {
                $gia = $gia_title = "(Đã bán)";
                $tinhtrang = "Đã bán";
                $daban = true;
            } else {
                $gia =$gia2= $line->pn;
                $gia = number_format($gia, 0, '', '.') . " đ";
                $tinhtrang = config('global.SIM_TINHTRANG');
                $gia_title = "giá " . $gia;
            }
            $simurrl = $sosim;
            $loai = mb_convert_case(config('global.LOAISIM')[$idloai], MB_CASE_TITLE, "UTF-8");
            $loai_url = khongdau(config('global.LOAISIM')[$idloai]);
            $ss = str_replace("+", "<br>", $line->f);
            $title = $sosim . ' - Sim Số Đẹp '.$line->f.' - ' . $this->domain;
            $des = $sosim . ' thuộc dòng '.$loai.' đầu số '.$mang.' có giá '.$gia.' tại '.$this->domain.'. Click Mua Ngay sim số đẹp '.$line->f.'.';

            $sim = array('SOSIM' => $ss,
                'SOSIMMOI' => $ss,
                'SOSIMMOIFULL' => $line->f,
                'SOSIMCU' => $line->f,
                'GIABAN' => $gia,
                'MANG' => $mang,
                'MANGIMG' => $mangimg,
                'LOAI' => $loai,
                'LOAIURL' => $loai_url,
                'TINHTRANG' => $tinhtrang,
                'SIMURL' => $simurrl,
                'SIM' => $sosim,
                'SIMFULL' => $line->f,
                'DABAN' => $daban,
                'GIABAN2' => $gia2
            );
            if (!empty($line->d)) {
                $duoi6 = substr($sosim, -6);
                $duoi4 = substr($sosim, -4);

                $paramsSearch = ['keyword' => '*' . $duoi6, 'rmS3' => true, 'simSid' => []];
                $suggestList = $this->elastic->search($paramsSearch);
                $suggest = [];
                if (!empty($suggestList['total']) && $suggestList['total'] > 10) {
                    $suggest = array_slice($suggestList['list'], 0,10);
                } elseif (!empty($suggestList['total']) && $suggestList['total'] == 10) {
                    $suggest = $suggestList['list'];
                } else {
                    $paramsSearch = ['keyword' => '*' . $duoi4, 'rmS3' => true, 'simSid' => []];
                    $suggestList4 = $this->elastic->search($paramsSearch);
                    if ($suggestList4['total'] > 0) {
                        $suggest4 = array_slice($suggestList4['list'], 0, 10); // laays 10 ban ghi
                        $suggest = array_merge($suggest, $suggest4); // merge
                        $suggest = array_slice($suggest, 0, 10); // tinh lai lay 5 ban ghi
                    }
                }
            }
        } else {
            $mang = checkmang($sosim);
            $mangimg = khongdau($mang);
            $idloai = get_cat_id2($sosim);
            $loai = mb_convert_case(config('global.LOAISIM')[$idloai], MB_CASE_TITLE, "UTF-8");
            $loai_url = khongdau(config('global.LOAISIM')[$idloai]);
            $loaisim = $loai;
            $gia = $gia_title = "(Đã bán)";
            $tinhtrang = "Đã bán";
            $simurrl = $sosim;
            $daban = true;
            $ss = str_replace("+", "<br>", $sosim);

            $title = $sosim . ' - Sim Số Đẹp '.$sosim.' - TOP SIM';
            $des = $sosim . ' thuộc dòng '.$loai.' đầu số '.$mang.' tại '.$this->domain.'. Click Mua Ngay sim số đẹp '.$sosim.'.';

            $duoi6 = substr($sosim, -6);
            $duoi4 = substr($sosim, -4);

            $paramsSearch = ['keyword' => '*' . $duoi6, 'rmS3' => true, 'simSid' => []];
            $suggestList = $this->elastic->search($paramsSearch);
            $suggest = [];
            if (!empty($suggestList['total']) && $suggestList['total'] > 10) {
                $suggest = array_slice($suggestList['list'], 0,10);
            } elseif (!empty($suggestList['total']) && $suggestList['total'] == 10) {
                $suggest = $suggestList['list'];
            } else {
                $paramsSearch = ['keyword' => '*' . $duoi4, 'rmS3' => true, 'simSid' => []];
                $suggestList4 = $this->elastic->search($paramsSearch);
                if ($suggestList4['total'] > 0) {
                    $suggest4 = array_slice($suggestList4['list'], 0, 10); // laays 10 ban ghi
                    $suggest = array_merge($suggest, $suggest4); // merge
                    $suggest = array_slice($suggest, 0, 10); // tinh lai lay 5 ban ghi
                }
            }

            $sim = array('SOSIM' => $ss,
                'SOSIMMOI' => $ss,
                'SOSIMMOIFULL' => $ss,
                'SOSIMCU' => $ss,
                'GIABAN' => $gia,
                'MANG' => $mang,
                'MANGIMG' => $mangimg,
                'LOAI' => $loai,
                'LOAIURL' => $loai_url,
                'TINHTRANG' => $tinhtrang,
                'SIMURL' => $simurrl,
                'SIM' => $sosim,
                'SIMFULL' => $ss,
                'DABAN' => $daban,
                'GIABAN2' => 0
            );
        }

        View::composer('*', function ($view) use ($title, $des, $sosim, $loai, $mang) {
            $view->with('web_title', ' Sim '.$sosim.' - Mua Sim '.$sosim.' Giá Rẻ Tại '.$this->domain);
            //$view->with('web_title', !empty($title) ? $title : 'Sim ' . $sosim . ' - Mua Sim ' . $sosim);
            $view->with('web_h1', !empty($title) ? $title : 'Sim ' . $sosim . ' - Mua Sim ' . $sosim);
            //$view->with('web_description', !empty($des) ? $des : 'Sim ' . $sosim . ' - Mua Sim ' . $sosim);
            $view->with('web_description', 'Sim ' . $sosim . ' Thuộc Dòng ' . $loai . ' Của Mạng ' . $mang . '. Mua Sim ' . $sosim . ' Giá Rẻ Hơn Tại ' . $this->domain . ', Freeship Toàn Quốc, ĐK Chính Chủ');
            $view->with('web_head', '');
            $view->with('web_foot', '');
        });
        return view($this->template . 'sim.detail', [
            'sim' => $sim,
            'suggest' => $suggest,
        ]);
    }

    public function actionOrder(Request $request) {
        // var_dump($request);die();
        $customerPhoneNumber              = $request->get('order_phone', '');
        $orderSim                      = $request->get('order_sim', '');
        $ignoreViolatedCase       = preg_replace("/[^0-9^*^x]/", "", $customerPhoneNumber);
        $ignoreViolatedCase       = preg_replace('/\*+/', '*', $ignoreViolatedCase);
        $firstTwoLetters          = substr($ignoreViolatedCase, 0, 2);
        $sqlQueryable             = str_replace('x', '_',
            str_replace('*', '%', $ignoreViolatedCase));
        $numberOfMeaningCharacter = strlen($ignoreViolatedCase)
                                    - substr_count($ignoreViolatedCase, '*')
                                    - substr_count($ignoreViolatedCase, 'x');
        if(!$this->isSpecificPhoneNumber($firstTwoLetters, $sqlQueryable, $ignoreViolatedCase, $numberOfMeaningCharacter)){
            throw new BadRequestHttpException("Vui lòng nhập đúng thông tin");
        }
        $attr = [
            'sosim' => $orderSim,
            'siminfo' => $request->get('order_price', '0') . ' ' . $request->get('order_telco', 'kxd'),
            'hoten' => $request->get('order_name', ''),
            'diachi' => $request->get('order_address', ''),
            'dienthoai' => $customerPhoneNumber,
            'yeucau' => 'mua sim',
            'ngaydathang' => date('Y-m-d H:i:s'),
            'capnhatcuoi' => date('Y-m-d H:i:s'),
            'cotrongkho' => 1,
            'nguon' => $this->domain,
            'ip' => $request->ip(),
            'tinhtrang' => 'moidat',
            'ghichu' => '',
            'domain' => $this->domain,
            'viewed' => 0
        ];
        try {

            $preOrder = $this->orders->isDuplicatedOrder($customerPhoneNumber, $orderSim);
            if ($preOrder) {
                return Response::json([
                    'created'       => false,
                    'errorMessages' => "Bạn đã đặt hàng thành công, đơn hàng của bạn đang được xử lí",
                ], 400);
            }
            $order = $this->orders->create($attr);
            return Response::json(array('created' => $order));
        } catch (ValidationException $e) {
            return Response::json(array('created' => false, 'errorMessages' => $e->getErrors()));
        }
    }

    public function actionCustomQuery(Request $request) {
        $params = $request->all();
        $perpage = config('global.perpageSim');
        $limit = !empty($params['perpage']) && (int)$params['perpage'] ? $params['perpage'] : $perpage;
        $pageNum = !empty($params['trang']) ? $params['trang'] : 1;
        if ($pageNum > $perpage) $pageNum = $perpage;
        $offsets = ($pageNum - 1) * $limit;
        $mang = isset($params["mang"]) ? $params["mang"] : false;
        $gia = isset($params["gia"]) ? $params["gia"] : false;
        $loai = isset($params["loai"]) ? $params["loai"] : false;
        $duoiso = isset($params["duoiso"]) ? $params["duoiso"] : false;
        $dauso = isset($params["dauso"]) ? $params["dauso"] : false;
        $tranh = isset($params["tranh"]) ? $params["tranh"] : false;
        $khotong = isset($params["khotong"]) ? $params["khotong"] : false;
        $anmakho = isset($params["anmakho"]) ? $params["anmakho"] : false;
        if (!$mang && !$gia && !$loai && !$duoiso) {
            return isset($params['khanhquery']) && ($params['khanhquery'] == 1) ? json_encode([]) : redirect()->home();
        }
        $arrayQuery = [];
        $searchParams = [];
        $title = 'Sim số đẹp';
        // loai sim
        if ($loai) {
            $LOAISIM = config('global.LOAISIM');
            $catItem = getCatIdByAlias($loai);
            $searchParams['cat_ids'] = array($catItem['cid']);
            $arrayQuery['loai'] = $loai;
            $title .= ' ' . $catItem['name'];
        }
        // Mang
        if ($mang) {
            $listLoaiMang = ['viettel', 'vinaphone', 'mobifone', 'vietnamobile', 'gmobile'];
            $mang2 = explode(',', $mang);
            if (is_array($mang2)) {
                foreach ($mang2 as $m) {
                    if (empty($m)) {
                        continue;
                    }
                    if (in_array($m, $listLoaiMang)) {
                        $id_mang = check_ten_mang($m);
                        if ($id_mang) {
                            $searchParams['telco_id'][] = $id_mang;
                            $arrayQuery['mang'][] = $m;
                            $title .= ' ' . $m;
                        }
                    }
                }
                if (!empty($arrayQuery['mang'])) {
                    $arrayQuery['mang'] = implode(',', $arrayQuery['mang']);
                }
            }
        }
        // Gia
        if ($gia) {
            $giaAr = explode(',', $gia);
            if ((count($giaAr) <= 1 || !is_numeric($giaAr[0]) || !is_numeric($giaAr[1])) || ($giaAr[0] == 0 && $giaAr[1] == 0)) {
                die;
            }
            $arrayQuery['gia'] = $gia;
            if ($giaAr[0] == 0 && $giaAr[1]) {
                //$title .= ' giá dưới ' . $gia;
                $searchParams['maxPrice'] = $giaAr[1];
            } elseif ($giaAr[1] == 0 && $giaAr[0]) {
                //$title .= ' giá trên ' . $gia;
                $searchParams['minPrice'] = $giaAr[0];
            } else {
                //$title .= ' giá từ ' . $giaAr[0] . ' đến ' . $giaAr[1];
                $searchParams['minPrice'] = $giaAr[0];
                $searchParams['maxPrice'] = $giaAr[1];
            }
        }
        if ($tranh) {
            $tranh = explode(',', $tranh);
            if (is_array($tranh)) {
                foreach ($tranh as $t) {
                    $t = trim($t);
                    if (!is_numeric(trim($t))) {
                        continue;
                    }
                    $searchParams['tranh'][] = trim($t);
                }
            }
        }
        // duoi so
        if ($duoiso) {
            $duoiso2 = explode(',', $duoiso);
            if (is_array($duoiso2)) {
                $searchParams['duoiso'] = [];
                foreach ($duoiso2 as $d) {
                    if (empty($d)) {
                        continue;
                    }
                    $duoiso = preg_replace('/\D/', '', $d);
                    $searchParams['duoiso'][] = $d;
                    $arrayQuery['duoiso'][] = $d;
                }
                if (!empty($arrayQuery['duoiso'])) {
                    $arrayQuery['duoiso'] = implode(',', $arrayQuery['duoiso']);
                }
                $title .= ' đuôi số ' . $arrayQuery['duoiso'];
            }
        }
        // dau so
        if ($dauso) {
            $dauso2 = explode(',', $dauso);
            if (is_array($dauso2)) {
                $searchParams['dauso'] = [];
                foreach ($dauso2 as $d) {
                    if (empty($d)) {
                        continue;
                    }
                    $searchParams['dauso'][] = $d;
                    $arrayQuery['dauso'][] = $d;
                }
                if (!empty($arrayQuery['dauso'])) {
                    $arrayQuery['dauso'] = implode(',', $arrayQuery['dauso']);
                }
                $title .= ' đầu số ' . $arrayQuery['dauso'];
            }
        }
        // ẩn mã kho
        if ($anmakho) {
            $anmakho2 = explode(',', $anmakho);
            if (is_array($anmakho2)) {
                $searchParams['anmakho'] = [];
                foreach ($anmakho2 as $d) {
                    if (empty($d)) {
                        continue;
                    }
                    $searchParams['anmakho'][] = $d;
                    $arrayQuery['anmakho'][] = $d;
                }
                if (!empty($arrayQuery['anmakho'])) {
                    $arrayQuery['anmakho'] = implode(',', $arrayQuery['anmakho']);
                }
                $title .= ' ẩn mã kho ' . $arrayQuery['anmakho'];
            }
        }
        $searchParams['page'] = $pageNum;
        $searchParams['limit'] = $limit;
        $searchParams['order'] = ['pn' => 'asc'];
        if ($khotong) {
            $searchParams['simSid'] = [];
        }
        $listSim = $this->elastic->search($searchParams);
        if (!empty($listSim['list'])) {
            echo json_encode($listSim['list']);
            exit;
        }
    }

    public function generatePagination($total = 5000, $url) {
        $items = range(0, $total);
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $itemCollection = collect($items);
        // Slice the collection to get the items to display in current page
        $currentPageItems = $itemCollection->slice(($currentPage * $this->perPage) - $this->perPage, $this->perPage)->all();

        // Create our paginator and pass it to the view
        $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $this->perPage);

        // set url path for generted links
        $paginatedItems->appends(request()->except('page'))->setPath($url);

        return $paginatedItems;
    }
    public function actionImageSim($alias) {
        $sosim = '0'.$alias;
        $data = $this->bangso->getBangsoTong($this->domain);
        $simInfo = searchArray($data, 'sim', $sosim);

        if (!empty($simInfo)) {
            $obj = [
                'i' => $simInfo[0]['sim'],
                'f' => $simInfo[0]['simfull'],
                'd' => false,
                'pn' => $simInfo[0]['price'],
                'c2' => $simInfo[0]['cat_id'],
            ];
            $simInfo = json_encode($obj);
        } else {
            $simInfo = '';
            if(class_exists('Memcached')){
                $m = new Memcached();
                $m->addServer('192.168.1.185', 11211);
                $s_order = md5(rand(1000, 9999));
                $simInfo = $m->get($sosim);
            }
            if (empty($simInfo)) {
                $paramsSearch = ['keyword' => $alias, 'rmS3' => true, 'simSid' => []];
                $dataSim = $this->elastic->search($paramsSearch);
                if (!empty($dataSim['list'])) {
                    $line = (object)$dataSim['list'][0];
                    $simInfo2 = [
                        'i' => $line->sim,
                        'f' => $line->simfull,
                        'd' => $line->price > 0 ? false : true,
                        'pn' => $line->price,
                        'c2' => $line->cat_id,
                    ];
                    $simInfo = json_encode($simInfo2);
                }
            }
        }
        $daban = false;
        $suggest = [];
        if (!empty($simInfo)) {
            $line = json_decode($simInfo);
            if (!isset($line->f)) {
                $line->f = $line->i;
            }
            $sosim = $line->i;
            $mang = checkmang($sosim);
            $mangimg = khongdau($mang);
            $idloai = $line->c2;
            $gia2 = 0;
            if ($line->d == true) {
                $gia = $gia_title = "(Đã bán)";
                $tinhtrang = "Đã bán";
                $daban = true;
            } else {
                $gia =$gia2= $line->pn;
                $gia = number_format($gia, 0, '', '.') . " đ";
                $tinhtrang = config('global.SIM_TINHTRANG');
                $gia_title = "giá " . $gia;
            }
            $simurrl = $sosim;
            $loai = mb_convert_case(config('global.LOAISIM')[$idloai], MB_CASE_TITLE, "UTF-8");
            $loai_url = khongdau(config('global.LOAISIM')[$idloai]);
            $ss = str_replace("+", "<br>", $line->f);
            $sim = array('SOSIM' => $ss,
                'SOSIMMOI' => $ss,
                'SOSIMMOIFULL' => $line->f,
                'SOSIMCU' => $line->f,
                'GIABAN' => $gia,
                'MANG' => $mang,
                'MANGIMG' => $mangimg,
                'LOAI' => $loai,
                'LOAIURL' => $loai_url,
                'TINHTRANG' => $tinhtrang,
                'SIMURL' => $simurrl,
                'SIM' => $sosim,
                'SIMFULL' => $line->f,
                'DABAN' => $daban,
                'GIABAN2' => $gia2
            );
        } else {
            $mang = checkmang($sosim);
            $mangimg = khongdau($mang);
            $idloai = get_cat_id2($sosim);
            $loai = mb_convert_case(config('global.LOAISIM')[$idloai], MB_CASE_TITLE, "UTF-8");
            $loai_url = khongdau(config('global.LOAISIM')[$idloai]);
            $loaisim = $loai;
            $gia = $gia_title = "(Đã bán)";
            $tinhtrang = "Đã bán";
            $simurrl = $sosim;
            $daban = true;
            $ss = str_replace("+", "<br>", $sosim);
            $sim = array('SOSIM' => $ss,
                'SOSIMMOI' => $ss,
                'SOSIMMOIFULL' => $ss,
                'SOSIMCU' => $ss,
                'GIABAN' => $gia,
                'MANG' => $mang,
                'MANGIMG' => $mangimg,
                'LOAI' => $loai,
                'LOAIURL' => $loai_url,
                'TINHTRANG' => $tinhtrang,
                'SIMURL' => $simurrl,
                'SIM' => $sosim,
                'SIMFULL' => $ss,
                'DABAN' => $daban,
                'GIABAN2' => 0
            );
        }
        echo createImage($sim['SIM'], $sim['SIMFULL'], $sim['GIABAN'], $sim['MANGIMG'], $sim['LOAI']);exit;
    }

    public function sitemap($alias){
        $content = $this->bangso->readSitemapAndRobots($alias.'.xml');
        return response($content, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }

    public function robots(){
        $content = $this->bangso->readSitemapAndRobots('robots.txt');
        return response($content, 200, [
            'Content-Type' => 'text/pain'
        ]);
    }
}
