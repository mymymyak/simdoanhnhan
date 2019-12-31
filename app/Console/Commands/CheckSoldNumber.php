<?php

namespace App\Console\Commands;

use App\Repositories\Bangso\BangsoRepository;
use App\Repositories\Domain\DomainRepository;
use App\Repositories\Elastic\ElasticInterface;
use App\Services\Cache\AppCache;
use App\Services\SimFilter;
use Illuminate\Console\Command;

class CheckSoldNumber extends Command {

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'checkSoldNumber';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description';

	protected $elasticRepository;

	protected $domainRepository;

	protected $bangSoRepository;

	private   $simFilterService;

	/**
	 * CheckSoldNumber constructor.
	 *
	 * @param ElasticInterface $elasticRepository
	 * @param DomainRepository $domainRepository
	 * @param BangsoRepository $bangSoRepository
	 */
	public function __construct (ElasticInterface $elasticRepository, DomainRepository $domainRepository, BangsoRepository $bangSoRepository) {
		$this->elasticRepository = $elasticRepository;
		$this->domainRepository  = $domainRepository;
		$this->bangSoRepository  = $bangSoRepository;
		$this->simFilterService  = new SimFilter($elasticRepository);
		parent::__construct();
	}

	/**
	 * @throws \App\Exceptions\Validation\ValidationException
	 */
	public function handle () {
		$domain = $this->domainRepository->getDomainActive()->where('is_updated_bangso', 0)->first();
		if ($domain) {
			$domain->update(['is_updated_bangso' => 1]);
			$this->bangSoRepository->setDomain($domain->domain);
			echo "Tên miền " . $domain->domain . "\n";
			$bangSoTong    = $this->bangSoRepository->getBangsoTong($domain->domain);
			$newBangSoTong = [];
			$limit         = 0;
			$simCollect    = [];
			$soldNumbers   = "";
			foreach ($bangSoTong as $index => $soTong) {
				$soSim = $this->getBeautyNumber($soTong['sim']);
				if ($limit < 300 && $index < count($bangSoTong) - 1) {
					$limit ++;
					$simCollect[] = $soSim;
					continue;
				}
				$searchResponseNumbers = $this->elasticRepository->searchByTerms($simCollect, $limit + 1);
				echo "-Số lương sim check lần này " . count($searchResponseNumbers) . "\n";
				foreach ($searchResponseNumbers as $searchResponseNumber) {
					echo "--Check số " . $searchResponseNumber['id'] . "\n";
					if (empty($searchResponseNumber) || (isset($searchResponseNumber['d']) && $searchResponseNumber['d'])) {
						/** Nếu số không tìm thấy hoặc tìm thấy nhưng đã bán */
						/** Loại khỏi bảng số tổng và lưu vào file so_da_ban.txt */
						$soldNumbers .= $searchResponseNumber['id'] . "\n";
						echo "Phát hiện số đã bán \n";
					} else {
						/** Số vẫn còn */
						$newBangSoTong[] = $searchResponseNumber['simfull'] . "\t" . $soTong['price'];
					}
					$limit      = 0;
					$simCollect = [];
				}
			}
			$bangSoTongNewContent = implode(PHP_EOL, $newBangSoTong);
			$this->bangSoRepository->saveDanhmuc([
				'danh_muc' => 'so_da_ban',
				'bangso'   => $soldNumbers,
			]);
			$this->bangSoRepository->setBangSoPath();
			$this->bangSoRepository->save(['bangso' => $bangSoTongNewContent]);
			$this->cacheHomePage($domain);
			$domain->update(['is_updated_bangso' => 2]);
		}
	}

	/**
	 * Cache lại trang chủ sau khi chạy xong cập nhật bảng sim mới
	 * Bao gồm cả 2 loại cache (trang chủ mặc định hoặc trang chủ shortcode)
	 *
	 * @param $domain
	 *
	 * @return mixed
	 */
	public function cacheHomePage ($domain) {
		$cacheSevice = new AppCache(app('cache'), 'getSimHotForHome');
		$keyEn       = serialize($domain->domain);
		$key         = 'homepage.bangsim.' . md5($keyEn);
		if ($cacheSevice->has($key)) {
			$cacheSevice->forget($key);
		}
		$key = 'homepage.bangsim.shortcode.' . md5($keyEn);
		if ($cacheSevice->has($key)) {
			return $cacheSevice->get($key);
		}
		$shortCodeItems = json_decode($domain->home_shortcode);
		$filterParams   = [];
		if ($shortCodeItems != null) {
			$filterParams = $this->simFilterService->getHomepageFilterParamsByShortCode($shortCodeItems);
		}
		$this->simFilterService->getSimHotForHome($domain->domain, $filterParams);
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
