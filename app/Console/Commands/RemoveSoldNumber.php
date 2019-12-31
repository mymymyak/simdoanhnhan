<?php

namespace App\Console\Commands;

use App\Repositories\Bangso\BangsoRepository;
use App\Repositories\Domain\DomainRepository;
use App\Repositories\Elastic\ElasticCache;
use App\Repositories\Elastic\ElasticInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemoveSoldNumber extends Command {

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'removeSoldNumber';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description';

	protected $domainRepository;

	protected $bangSoRepository;

	protected $elasticRepository;

	/**
	 * removeSoldNumber constructor.
	 *
	 * @param ElasticInterface $elasticRepository
	 * @param DomainRepository $domainRepository
	 * @param BangsoRepository $bangSoRepository
	 */
	public function __construct (ElasticInterface $elasticRepository, DomainRepository $domainRepository, BangsoRepository $bangSoRepository) {
		$this->domainRepository  = $domainRepository;
		$this->bangSoRepository  = $bangSoRepository;
		$this->elasticRepository = $elasticRepository;
		parent::__construct();
	}

	/**
	 * @throws \App\Exceptions\Validation\ValidationException
	 */
	public function handle () {
		$domain = $this->domainRepository->getDomainActive()->where('is_updated_bangso', 2)->first();
		$this->bangSoRepository->setDomain($domain->domain);
		$soDaBan   = $this->bangSoRepository->getBangsoDetail([
			'danh_muc' => 'so_da_ban',
		]);
		$fileLines = explode(PHP_EOL, $soDaBan);
		if(!empty($fileLines)){
			foreach ($fileLines as $fileLine) {
				$categoryTxtFiles = array_keys($this->getLoaiSim());
				foreach ($categoryTxtFiles as $categoryTxtFile) {
					if ($categoryTxtFile != "") {
						echo "--- Check file: " . $categoryTxtFile . "\n";
						$newFileLines = $this->checkIfNumberExistToRemove($fileLine, $categoryTxtFile);
						$this->bangSoRepository->saveDanhmuc([
							'bangso'   => $newFileLines,
							'danh_muc' => $categoryTxtFile,
						]);
						$this->cacheSimTypeCategory($categoryTxtFile);
					}
				}
			}
			$domain->update(['is_updated_bangso' => 0]);
			/*$count = DB::table('tbl_domain')->where('is_updated_bangso', '!=', 4)->count();
			if (!$count) {
				DB::table('tbl_domain')->update(['is_updated_bangso' => 0]);
			}*/
		}
	}

	private function getLoaiSim () {
		$loaisim                                     = config('global')['LOAISIM'];
		$loaisim2                                    = ['' => 'Chọn danh mục'];
		$loaisim2['homepage3mang']                   = '3 Mạng hiển thị trang chủ';
		$loaisim2['simvip3mang']                     = 'Sim vip 3Q mạng trang chủ';
		$loaisim2['sim-gia-duoi-500-nghin']          = 'Sim Giá dưới 500 nghìn';
		$loaisim2['sim-gia-500-nghin-den-1-trieu']   = 'Sim giá 500 nghìn đến 1 triệu';
		$loaisim2['sim-gia-1-trieu-den-3-trieu']     = 'Sim giá 1 triệu đến 3 triệu';
		$loaisim2['sim-gia-3-trieu-den-5-trieu']     = 'Sim giá 3 triệu đến 5 triệu';
		$loaisim2['sim-gia-5-trieu-den-10-trieu']    = 'Sim giá 5 triệu đến 10 triệu';
		$loaisim2['sim-gia-10-trieu-den-50-trieu']   = 'Sim giá 10 triệu đến 50 triệu';
		$loaisim2['sim-gia-50-trieu-den-100-trieu']  = 'Sim giá 50 triệu đến 100 triệu';
		$loaisim2['sim-gia-100-trieu-den-200-trieu'] = 'Sim giá 100 triệu đến 200 triệu';
		$loaisim2['sim-gia-tren-200-trieu']          = 'Sim giá trên 200 triệu';
		$loaisim2['sim-viettel']                     = 'Sim viettel';
		$loaisim2['sim-vinaphone']                   = 'Sim vinaphone';
		$loaisim2['sim-mobifone']                    = 'Sim mobifone';
		$loaisim2['sim-vietnamobile']                = 'Sim vietnamobile';
		$loaisim2['sim-gmobile']                     = 'Sim gmobile';
		$loaisim2['sim-itelecom']                    = 'Sim itelecom';
		foreach ($loaisim as $key => $loai) {
			if (in_array($key, [
				110,
				111,
				112,
				113,
				114,
				120,
				121,
				122,
				123,
				124,
				125,
			])) {
				continue;
			}
			$loaisim2[khongdau($loai)] = $loai;
		}
		$loaisim2['sim-gia-re'] = 'Sim giá rẻ';
		return $loaisim2;
	}

	/**
	 * Kiểm tra trong file danh mục nếu có tồn tại thì loại bỏ
	 *
	 * @param $soSim
	 * @param $fileName
	 *
	 * @return string
	 */
	private function checkIfNumberExistToRemove ($soSim, $fileName) {
		$categoryBangSo = $this->bangSoRepository->getBangsoDetail([
			'danh_muc' => $fileName,
		]);
		$fileLines      = explode(PHP_EOL, $categoryBangSo);
		$newFileData    = [];
		if (!empty($fileLines)) {
			foreach ($fileLines as $originNumber) {
				if ($originNumber != "") {
					$line = $this->getBeautyNumber($originNumber);
					$line = trim($line);
					if ($soSim === $line || strpos($soSim, $line)) {
						echo "---Phát hiện số đã bán \n";
						//continue;
					}
					$newFileData[] = $originNumber;
				}
			}
		}
		return implode(PHP_EOL, $newFileData);
	}

	/**
	 * Cache loại sim theo danh mục (mặc định cache 5 page đầu)
	 *
	 * @param $alias
	 */
	private function cacheSimTypeCategory ($alias) {
		$telco        = check_ten_mang($alias);
		$paramsSearch = [
			'currentUrl'  => $alias,
			'search_ajax' => false,
		];
		if ($telco) {
			$paramsSearch['telco_id'] = [$telco];
			/** Cache khi chưa có params filter */
			for ($i = 1; $i <= 5; $i ++) {
				$this->syncSearchParamsAndCache(array_merge($paramsSearch, ['page' => $i]));
			}
			/** Filter nâng cao cho telco */
			$headNumbers = $this->getTelCoHeadNumbers($telco);
			foreach ($headNumbers as $headNumber) {
				$paramsSearch['mm'] = $headNumber;
				foreach ($this->getGeneralPrice() as $price) {
					$paramsSearch = array_merge($paramsSearch, $this->getBeautyPrice($price));
					for ($i = 1; $i <= 5; $i ++) {
						/** Cache khi có advance search params */
						$this->syncSearchParamsAndCache(array_merge($paramsSearch, ['page' => $i]));
					}
				}
			}
		} else {
			$catItem = getCatIdByAlias($alias);
			if (!empty($catItem)) {
				$paramsSearch['cat_ids'] = $catItem['cid'];
				/** Cache khi chưa có params filter */
				for ($i = 1; $i <= 5; $i ++) {
					$this->syncSearchParamsAndCache(array_merge($paramsSearch, ['page' => $i]));
				}
				/** Filter nâng cao cho loại sim */
				$listPrice = $this->getSimTypePrice(trim($alias));
				foreach ($listPrice as $price) {
					$paramsSearch = array_merge($paramsSearch, $this->getBeautyPrice($price));
					foreach ($this->getTelcoId() as $telcoId) {
						$paramsSearch['telco_id'] = [$telcoId];
						/** Cache khi có advance search params */
						$this->syncSearchParamsAndCache(array_merge($paramsSearch, ['page' => $i]));
					}
				}
			}
		}
	}

	private function syncSearchParamsAndCache ($params) {
		$filter      = $params;
		$searchParam = [];
		if (!empty($params['isHome'])) {
		} elseif (!empty($params['cat_ids'])) { // danh muc
			$filter['cat_ids']      = $params['cat_ids'];
			$searchParam['cat_ids'] = array($params['cat_ids']);
		} elseif (!empty($params['yid'])) { // nam sinh
			$filter['yid']          = $params['yid'];
			$searchParam['keyword'] = '*' . $params['yid'];
		} elseif (!empty($params['dauso'])) { // Dau so
			$dauso = $params['dauso'];
			if (is_array($dauso)) {
				$dauso = implode(",", $params['dauso']);
			}
			$filter['search']['dauso'] = $dauso;
			$searchParam['dauso']      = [$dauso];
			$customSlug                = $dauso;
		} elseif (!empty($params['duoiso'])) { // Dau so
			$filter['search']['duoiso'] = $params['duoiso'];
			$searchParam['keyword']     = '*' . $params['duoiso'];
		} elseif (!empty($params['telco_id'])) { // nam sinh
			$searchParam['telco_id'] = $params['telco_id'];
		} elseif (!empty($params['simtragop'])) {
			$searchParam['simSid']   = [2211];
			$searchParam['minPrice'] = 10000000;
			$searchParam['minPrice'] = 10000000;
		}
		if (!empty($params['loaidauso'])) { // Loai Dau so Duoi so
			$dauso                      = $params['loaiData'][0];
			$duoiso                     = $params['loaiData'][1];
			$filter['search']['dauso']  = $dauso;
			$filter['search']['duoiso'] = $duoiso;
			$searchParam['keyword']     = $dauso . '*' . $duoiso;
		}
		$searchParam = array_merge($searchParam, $filter);
		if ($this->elasticRepository instanceof ElasticCache) {
			$this->elasticRepository->clearSearchCache($searchParam);
			$this->elasticRepository->search($searchParam);
		}
	}

	public function getTelCoHeadNumbers ($telcoId) {
		$arrayDauso = [];
		switch ($telcoId) {
			case 1:
				$arrayDauso = [
					'09',
					'08',
					'03',
				];
				break;
			case 2:
				$arrayDauso = [
					'09',
					'08',
				];
				break;
			case 3:
				$arrayDauso = [
					'09',
					'08',
					'07',
				];
				break;
			case 4:
			case 5:
				$arrayDauso = [
					'09',
					'05',
				];
				break;
			case 8:
				$arrayDauso = ['08'];
				break;
		}
		return $arrayDauso;
	}

	private function getSimTypePrice ($simType) {
		$arrayGia = [];
		switch ($simType) {
			case 'sim-luc-quy':
				$arrayGia = [
					'200_300',
					'300_500',
					'500_0',
				];
				break;
			case 'sim-luc-quy-giua':
				$arrayGia = [
					'10_15',
					'15_20',
					'20_50',
					'50_100',
					'100_0',
				];
				break;
			case 'sim-ngu-quy':
				$arrayGia = [
					'20_50',
					'50_100',
					'100_0',
				];
				break;
			case 'sim-ngu-quy-giua':
				$arrayGia = [
					'1_2',
					'2_3',
					'3_5',
					'5_8',
					'8_10',
					'10_15',
					'15_20',
					'20_50',
					'50_100',
					'100_0',
				];
				break;
			case 'sim-tu-quy':
				$arrayGia = [
					'3_5',
					'5_8',
					'8_10',
					'10_15',
					'15_20',
					'20_50',
					'50_100',
					'100_0',
				];
				break;
			case 'sim-tra-gop':
				$arrayGia = [
					'10_15',
					'15_20',
					'20_50',
					'50_100',
					'100_0',
				];
				break;
			case 'sim-gia-re':
				$arrayGia = [
					'0_1',
					'1_2',
				];
				break;
		}
		return $arrayGia;
	}

	private function getBeautyPrice ($price) {
		$temp      = explode("_", $price);
		$price_min = isset($temp[0]) ? intval($temp[0]) : 0;
		$price_max = isset($temp[1]) ? intval($temp[1]) : 0;
		return [
			'minPrice' => $price_min . '000000',
			'maxPrice' => $price_max . '000000',
		];
	}

	private function getGeneralPrice () {
		return [
			'0_1',
			'1_2',
			'2_3',
			'3_5',
			'5_8',
			'8_10',
			'10_15',
			'15_20',
			'20_50',
			'50_100',
			'100_0',
		];
	}

	private function getTelcoId () {
		return [
			1,
			2,
			3,
			4,
			5,
			6,
			7,
			9,
		];
	}

	private function getBeautyNumber ($number) {
		$number = str_replace([
			',',
			'.',
		], [
			'',
			'',
		], $number);
		return trim($number);
	}
}
