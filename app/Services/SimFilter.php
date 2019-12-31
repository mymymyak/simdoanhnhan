<?php
/**
 * Created by PhpStorm.
 * User: DUYDUC
 * Date: 16-Jul-19
 * Time: 10:15 AM
 */

namespace App\Services;

use App\Repositories\Elastic\ElasticInterface;
use App\Services\Cache\AppCache;

class SimFilter {

	protected $perPage      = 50;

	protected $elastic;

	protected $filter       = [];

	protected $searchParame = [];

	protected $domain;

	protected $path;

	public function __construct (ElasticInterface $elastic) {
		$this->perPage = !empty(config('global.perpageSim')) ? config('global.perpageSim') : $this->perPage;
		$this->elastic = $elastic;
		$this->domain  = request()->server('HTTP_HOST');
		$this->path    = public_path($this->domain);
	}

	public function filterSimCondition ($value) {
		$condition = [
			'mm'       => 0,
			'minPrice' => 0,
			'maxPrice' => 0,
			'telco'    => 0,
		];
		if (!empty($this->filter)) {
			$condition = $this->filter;
		}
		$arrayCondition = [];
		if (isset($condition['mm']) && $condition['mm'] != 0) {
			$dau2  = substr($value['sim'], 0, 2);
			$dau3  = substr($value['sim'], 0, 3);
			$array = [$condition['mm']];
			if (!empty($array)) {
				$arrayCondition[] = in_array($dau2, $array) || in_array($dau3, $array);
			}
		}
		if (isset($condition['minPrice']) && isset($condition['maxPrice']) && $condition['minPrice'] >= 0 && $condition['maxPrice'] == 0) {
			$arrayCondition[] = $value['price'] >= $condition['minPrice'];
		}
		if (isset($condition['minPrice']) && isset($condition['maxPrice']) && $condition['minPrice'] == 0 && $condition['maxPrice'] > 0) {
			$arrayCondition[] = $value['price'] <= $condition['maxPrice'];
		}
		if (isset($condition['minPrice']) && isset($condition['maxPrice']) && $condition['minPrice'] > 0 && $condition['maxPrice'] > 0) {
			$arrayCondition[] = $value['price'] >= $condition['minPrice'] && $value['price'] <= $condition['maxPrice'];
		}
		if (!empty($condition['search']) && !empty($condition['search']['dauso']) && empty($condition['search']['duoiso'])) {
			$length           = strlen($condition['search']['dauso']);
			$dauso            = substr($value['sim'], 0, $length);
			$arrayCondition[] = $dauso == $condition['search']['dauso'];
		} elseif (!empty($condition['search']) && empty($condition['search']['dauso']) && !empty($condition['search']['duoiso'])) {
			$length           = strlen($condition['search']['duoiso']);
			$duoiso           = substr($value['sim'], - $length);
			$arrayCondition[] = $duoiso == $condition['search']['duoiso'];
		} elseif (!empty($condition['search']) && !empty($condition['search']['dauso']) && !empty($condition['search']['duoiso'])) {
			$lengthDauso      = strlen($condition['search']['dauso']);
			$lengthDuoiso     = strlen($condition['search']['duoiso']);
			$dauso            = substr($value['sim'], 0, $lengthDauso);
			$duoiso           = substr($value['sim'], - $lengthDuoiso);
			$arrayCondition[] = $dauso == $condition['search']['dauso'] && $duoiso == $condition['search']['duoiso'];
		}
		if (!empty($condition['cat_ids'])) {
			$arrayCondition[] = is_array($condition['cat_ids']) && $value['cat_id'] == $condition['cat_ids'][0];
		}
		if (!empty($condition['yid'])) {
			$length           = strlen($condition['yid']);
			$duoiso           = substr($value['sim'], - $length);
			$arrayCondition[] = $duoiso == $condition['yid'];
		}
		if (!empty($condition['telco_id'])) {
			$arrayCondition[] = $value['telco'] == $condition['telco_id'][0];
		}
		return !in_array(false, $arrayCondition);
	}

	public function locBangSim ($data) {
		if (empty($data)) {
			return [];
		}
		return array_filter($data, array(
			$this,
			'filterSimCondition',
		));
	}

	public function invenDescSort ($item1, $item2) {
		if ($item1['price'] == $item2['price']) {
			return 0;
		}
		return ($item1['price'] < $item2['price']) ? 1 : - 1;
	}

	public function invenAscSort ($item1, $item2) {
		if ($item1['price'] == $item2['price']) {
			return 0;
		}
		return ($item1['price'] > $item2['price']) ? 1 : - 1;
	}

	public function isDuplicate ($listData, $value) {
		return strpos(json_encode($listData), $value) !== false ? true : false;
	}

	public function boUuTien () {
		$loai = [];
		if (file_exists($this->path . '/bo_uu_tien.txt')) {
			$dataUt1 = file_get_contents($this->path . '/bo_uu_tien.txt');
			$dataUt1 = explode(PHP_EOL, $dataUt1);
			if (!empty($dataUt1)) {
				foreach ($dataUt1 as $url) {
					$s = explode('|', $url);
					if (isset($s[1])) {
						$loai[$s[0]] = $s[1];
					}
				}
			}
		}
		return $loai;
	}

	public function getSimByCondition ($params = null) {
		$this->filter = $params;
		if ($params == null) {
			return null;
		}
		$dir      = $this->path;
		$boUutien = $this->boUuTien();
		$flagUt   = true; // luôn uu tiên
		if (isset($boUutien[$params['currentUrl']]) && (int) $boUutien[$params['currentUrl']] > 0) {
			$flagUt = false;
		}
		$pageNum = isset($params['page']) && (int) $params['page'] > 0 ? $params['page'] : 1;
		$limit   = $this->perPage;
		/*if (empty($this->filter['search_ajax']) && $pageNum) {
			$limit += 12; // Lấy thêm sim hot
		}*/
		$offset     = ($pageNum - 1) * $limit;
		$customSlug = '';
		if (!empty($params['isHome'])) {
			$customSlug = 'trang-chu';
		} elseif (!empty($params['cat_ids'])) { // danh muc
			$this->filter['cat_ids']       = $params['cat_ids'];
			$this->searchParame['cat_ids'] = array($params['cat_ids']);
			$text                          = getLoaiSimByCatId($params['cat_ids']);
			$customSlug                    = khongdau($text);
		} elseif (!empty($params['yid'])) { // nam sinh
			$this->filter['yid']           = $params['yid'];
			$this->searchParame['keyword'] = '*' . $params['yid'];
			$customSlug                    = $params['yid'];
		} elseif (!empty($params['dauso'])) { // Dau so
			$dauso = $params['dauso'];
			if (is_array($dauso)) {
				$dauso = implode(",", $params['dauso']);
			}
			$this->filter['search']['dauso'] = $dauso;
			$this->searchParame['dauso']     = [$dauso];
			$customSlug                      = $dauso;
		} elseif (!empty($params['duoiso'])) { // Dau so
			$this->filter['search']['duoiso'] = $params['duoiso'];
			$this->searchParame['keyword']    = '*' . $params['duoiso'];
			$customSlug                       = $params['duoiso'];
		} elseif (!empty($params['price'])) { // Price
			$min                      = $params['dataPrice']['min'];
			$max                      = $params['dataPrice']['max'];
			$this->filter['minPrice'] = $min;
			$this->filter['maxPrice'] = $max;
			if ($min == 0 && $max > 0) {
				$this->searchParame['maxPrice'] = $max;
			} elseif ($min > 0 && $max == 0) {
				$this->searchParame['minPrice'] = $min;
			} else {
				$this->searchParame['minPrice'] = $min;
				$this->searchParame['maxPrice'] = $max;
			}
		} elseif (!empty($params['telco_id'])) { // nam sinh
			$this->filter['telco_id']       = $params['telco_id'];
			$this->searchParame['telco_id'] = $params['telco_id'];
		} elseif (!empty($params['simtragop'])) {
			$this->searchParame['simSid']   = [2211];
			$this->searchParame['minPrice'] = 10000000;
			$this->filter['minPrice']       = 10000000;
		}
		if (empty($customSlug)) {
			$customSlug = $params['currentUrl'];
		}
		if (!empty($params['loaidauso'])) { // Loai Dau so Duoi so
			$dauso                            = $params['loaiData'][0];
			$duoiso                           = $params['loaiData'][1];
			$this->filter['search']['dauso']  = $dauso;
			$this->filter['search']['duoiso'] = $duoiso;
			$this->searchParame['keyword']    = $dauso . '*' . $duoiso;
		}
		$this->searchParame = array_merge($this->searchParame, $this->filter);
		if (empty($this->searchParame['telco_id']) && empty($params['dauso'])) {
			//$this->filter['telco_id'] = [1];
			//$this->searchParame['telco_id'] = array(1);
		}

		$listSim = [];
		// =========================== UU TIEN ============================
		if ($flagUt) {
			$data = [];
			if (file_exists($this->path . '/bangsodata.txt')) {
				$data = file_get_contents($this->path . '/bangsodata.txt');
				$data = json_decode($data, true);
			}
			$fileName = $params['currentUrl'];
			if (empty($this->filter['search_ajax']) && file_exists($dir . '/' . $fileName . '.txt')) {
				$dataUt1 = file_get_contents($dir . '/' . $fileName . '.txt');
				$arrUt1  = $listUT1 = [];
				if (!empty($dataUt1)) {
					$dataUt1 = explode(PHP_EOL, $dataUt1);
					if (!empty($dataUt1)) {
						foreach ($dataUt1 as $ut) {
							if (!isset($ut[0])) {
								continue;
							}
							$ut   = str_replace([
								',',
								'.',
							], [
								'',
								'',
							], $ut);
							$ut   = $ut[0] == '0' ? $ut : '0' . $ut;
							$ut   = trim($ut);
							$item = $this->array_find($ut, $data);
							if (!empty($item)) {
								$arrUt1[] = $item;
							}
						}
					}
					if (!empty($arrUt1)) {
						$listUT1 = array_slice($arrUt1, $offset, $limit);
					}
					$listSim = $listUT1;
				} else {
					$data    = $this->locBangSim($data);
					$listSim = array_slice($data, $offset, $limit);
				}
				if (count($listUT1) < $limit) {
					$data = $this->locBangSim($data);
					$listSim = array_slice($data, $offset,$limit - count($listUT1));
					$listSim = array_merge($listUT1,$listSim);
				} else {
					$listSim = $listUT1;
				}
			} else {
				$data    = $this->locBangSim($data);
				$listSim = array_slice($data, $offset, $limit);
			}
		}
		$count = count($listSim);

		$key   = 'empty_' . md5(serialize($this->searchParame));
		if ($count < $limit) {
			// custom query
			$link = $this->getCustomQueryBySlug((string) $params['currentUrl']);
			if ($link != "") {
				$customQueriesName = [
					'global',
					(string) $params['currentUrl'],
				];
				if ($this->getCustomQueryConfiguration($customQueriesName) != null) {
					$link                          .= '&anmakho=' . $this->getCustomQueryConfiguration($customQueriesName);
					$this->searchParame['anmakho'] = $this->getCustomQueryConfiguration($customQueriesName);
				}
			}
			if (empty($this->filter['search_ajax']) && $link != '') {
				$link                     = $pageNum > 1 ? $link . '&trang=' . $pageNum : $link;
				$link                     = trim($link) . '&perpage=' . $limit;
				$customQueryUrlComponents = parse_url($link);
				parse_str($customQueryUrlComponents['query'], $customQueryParams);
				$priorityParam = $this->convertCustomQueryParamToPriorityParam($customQueryParams);
				$customList    = $this->elastic->search($priorityParam);
				$listSim       = array_merge($listSim, $customList['list']);
			}
			$count = count($listSim);
			if ($count < $limit) {
				// kho uu tien
				if (empty($_SESSION[$key])) {
					$this->searchParame = array_merge($this->searchParame, $this->filter);
					$dataSim            = $this->elastic->search($this->searchParame);
					if (!empty($dataSim['list'])) {
						$listSim2 = $dataSim['list'];
						if (!empty($listSim2)) {
							for ($i = 0; $i < $limit - $count; $i ++) {
								if (empty($listSim2[$i])) {
									continue;
								}
								if ($this->isDuplicate($listSim, $listSim2[$i]['sim'])) {
									continue;
								}
								$listSim[] = $listSim2[$i];
							}
						} else {
							$_SESSION[$key] = true;
						}
					}
				}
				// xoa kho uu tien lay tiep
				if ($count < $limit) {
					$this->searchParame['simSid'] = [];
					$this->searchParame           = array_merge($this->searchParame, $this->filter);
					$dataSim = $this->elastic->search($this->searchParame);
					if (!empty($dataSim['list'])) {
						$listSim2 = $dataSim['list'];
						if (!empty($listSim2)) {
							for ($i = 0; $i < $limit - $count; $i ++) {
								if (empty($listSim2[$i])) {
									continue;
								}
								if ($this->isDuplicate($listSim, $listSim2[$i]['sim'])) {
									continue;
								}
								$listSim[] = $listSim2[$i];
							}
						}
					}
				}
			}
		}
		if (count($listSim) > $limit) {
			$listSim = array_slice($listSim, 0, $limit);
		}
		$keySimhot = 'listSimHot' . md5($params['currentUrl']);
		/*if ($pageNum == 1 && empty($this->filter['search_ajax'])) {
			unset($_SESSION[$keySimhot]);
		}
		if (!$_SESSION[$keySimhot]) {
			$_SESSION[$keySimhot] = array_slice($listSim, 0,12);
			$listSim = array_slice($listSim, count($_SESSION[$keySimhot]),count($listSim));
		}*/
		//   var_dump($_SESSION[$keySimhot]);
		$shuffle = true;
		if (!empty($this->filter['order']) && $this->filter['order']['giaban'] == 'ASC') {
			usort($listSim, array(
				$this,
				'invenAscSort',
			));
			$shuffle = false;
		} elseif (!empty($this->filter['order']) && $this->filter['order']['giaban'] == 'DESC') {
			usort($listSim, array(
				$this,
				'invenDescSort',
			));
			$shuffle = false;
		}
		if ($shuffle) {
			shuffle($listSim);
		}
		return $listSim;
	}

	public function convertCustomQueryParamToPriorityParam ($params = []) {
		$priorityParams = [];
		if (isset($params['gia'])) {
			$price = explode(",", $params['gia']);
			if (isset($price[0])) {
				$priorityParams['minPrice'] = $price[0];
			}
			if (isset($price[1])) {
				$priorityParams['maxPrice'] = $price[1];
			}
		}
		if (isset($params['telco_id'])) {
			$telco                      = explode(",", $params['telco_id']);
			$priorityParams['telco_id'] = $telco;
		}
		if (isset($params['dauso'])) {
			$headNumber = explode(",", $params['dauso']);
			if (is_array($headNumber)) {
				foreach ($headNumber as $head) {
					$priorityParams['dauso'][] = $head;
				}
			}
		}
		if (isset($params['duoiso'])) {
			$tailNumber = explode(",", $params['duoiso']);
			if (is_array($tailNumber)) {
				foreach ($tailNumber as $tail) {
					$priorityParams['duoiso'][] = $tail;
				}
			}
		}
		if (isset($params['tranh'])) {
			$voidNumber = explode(",", $params['tranh']);
			if (is_array($voidNumber)) {
				foreach ($voidNumber as $void) {
					$priorityParams['tranh'][] = $void;
				}
			}
		}
		if (isset($params['loai'])) {
			$catItem = getCatIdByAlias($params['loai']);
			if ($catItem != "" && is_array($catItem)) {
				$priorityParams['cat_ids'] = $catItem['cid'];
			}
		}
		return $priorityParams;
	}

	public function getCustomQueryBySlug ($slug) {
		if (file_exists($this->path . '/custom_query.txt')) {
			if ($file = fopen($this->path . "/custom_query.txt", "r")) {
				while (!feof($file)) {
					$line = fgets($file);
					$line = trim($line);
					$line = explode('|', $line);
					if ($slug === (string) $line[0]) {
						fclose($file);
						return $line[1];
					}
				}
				fclose($file);
			}
		}
		return null;
	}

	private function getCustomQueryConfiguration (array $customQueriesName) {
		if (file_exists($this->path . '/custom_query_configuration.txt')) {
			if ($configurationFile = fopen($this->path . "/custom_query_configuration.txt", "r")) {
				$queryString = "";
				while (!feof($configurationFile)) {
					$configurationLine = fgets($configurationFile);
					$configurationLine = trim($configurationLine);
					$configuration     = explode('|', $configurationLine);
					if (isset($configuration[0]) && isset($configuration[1]) && in_array((string) $configuration[0], $customQueriesName)) {
						if ($queryString != "") {
							$queryString .= ",";
						}
						$queryString .= (string) $configuration[1];
					}
				}
				return $queryString;
			}
		}
		return null;
	}

	public function getSimByConditionTrasauKM ($params = null) {
		if ($params == null) {
			return null;
		}
		$pageNum = isset($params['page']) && (int) $params['page'] > 0 ? $params['page'] : 1;
		$limit   = $this->perPage;
		$offset  = ($pageNum - 1) * $limit;
		$cacheSevice = new AppCache(app('cache'), 'getSimByConditionTrasauKM');
		$key         = 'getSimByConditionTrasauKM.' . md5($pageNum . $limit . $offset);
		if ($cacheSevice->has($key)) {
			return $cacheSevice->get($key);
		}
		$dir = $this->path;
		if ($params['currentUrl'] == 'sim-tra-sau') {
			$fileName = 'sim-tra-sau.txt';
		} elseif ($params['currentUrl'] == 'sim-gia-re') {
			$fileName = 'sim-gia-re.txt';
		} else {
			return null;
		}
		$dataBsTong = [];
		if (file_exists($this->path . '/bangsodata.txt')) {
			$dataBsTong = file_get_contents($this->path . '/bangsodata.txt');
			$dataBsTong = json_decode($dataBsTong, true);
		}
		// lay data file
		$data = [];
		if (file_exists($dir . '/' . $fileName)) {
			$fileContent = file_get_contents($dir . '/' . $fileName);
			$dataUt1     = explode(PHP_EOL, $fileContent);
			if (!empty($dataUt1)) {
				foreach ($dataUt1 as $ut) {
					if (!isset($ut[0])) {
						continue;
					}
					$ut   = str_replace([
						',',
						'.',
					], [
						'',
						'',
					], $ut);
					$ut   = $ut[0] == '0' ? $ut : '0' . $ut;
					$ut   = trim($ut);
					$item = $this->array_find($ut, $dataBsTong);
					if (!empty($item)) {
						$data[] = $item;
					}
				}
			}
		}
		$data    = $this->locBangSim($data);
		$listSim = array_slice($data, $offset, $limit);
		$count   = count($listSim);
		if ($count < $limit) {
			// kho uu tien
			$this->searchParame['page']     = $pageNum;
			$this->searchParame             = array_merge($this->searchParame, $this->filter);
			$this->searchParame['simSid']   = [3588];
			$this->searchParame['minPrice'] = 250000;
			$this->searchParame['maxPrice'] = 499000;
			$this->searchParame['limit']    = $limit;
			$this->searchParame             = array_merge($this->searchParame, $this->filter);
			$dataSim = $this->elastic->search($this->searchParame);
			if (!empty($dataSim['list'])) {
				$listSim2 = $dataSim['list'];
				if (!empty($listSim2)) {
					for ($i = 0; $i < $limit - $count; $i ++) {
						if (empty($listSim2[$i])) {
							continue;
						}
						if ($this->isDuplicate($listSim, $listSim2[$i]['sim'])) {
							continue;
						}
						$listSim[] = $listSim2[$i];
					}
				}
			}
		}
		if (count($listSim) > $limit) {
			$listSim = array_slice($listSim, 0, $limit);
		}
		$shuffle = true;
		if (!empty($this->filter['order']) && $this->filter['order']['giaban'] == 'ASC') {
			usort($listSim, array(
				$this,
				'invenAscSort',
			));
			$shuffle = false;
		} elseif (!empty($this->filter['order']) && $this->filter['order']['giaban'] == 'DESC') {
			usort($listSim, array(
				$this,
				'invenDescSort',
			));
			$shuffle = false;
		}
		if ($shuffle) {
			//shuffle($listSim);
		}
		$cacheSevice->put($key, $listSim, 600);
		return $listSim;
	}

	public function simGanGiong ($params) {
		if ($params == null) {
			return null;
		}
		$pageNum = isset($params['page']) && (int) $params['page'] > 0 ? $params['page'] : 1;
		$limit   = $this->perPage;
		$offset  = ($pageNum - 1) * $limit;
		$dir     = $this->path;
		$data    = [];
		if (file_exists($dir . '/bangsodata.txt')) {
			$data = file_get_contents($dir . '/bangsodata.txt');
			$data = json_decode($data, true);
		}
		$this->filter['search']['duoiso'] = $params['duoiso'];
		$this->searchParame['keyword']    = '*' . $params['duoiso'];
		$customSlug                       = $params['duoiso'];
		$data                             = $this->locBangSim($data);
		$listSim                          = array_slice($data, $offset, $limit);
		$count                            = count($listSim);
		if ($count < $limit) {
			// kho uu tien
			$this->searchParame['page']  = $pageNum;
			$this->searchParame          = array_merge($this->searchParame, $this->filter);
			$this->searchParame['limit'] = $limit;
			$dataSim                     = $this->elastic->search($this->searchParame);
			if (!empty($dataSim['list'])) {
				$listSim2 = $dataSim['list'];
				if (!empty($listSim2)) {
					for ($i = 0; $i < $limit - $count; $i ++) {
						if (empty($listSim2[$i])) {
							continue;
						}
						if ($this->isDuplicate($listSim, $listSim2[$i]['sim'])) {
							continue;
						}
						$listSim[] = $listSim2[$i];
					}
				}
			}
		}
		if (count($listSim) > $limit) {
			$listSim = array_slice($listSim, 0, $limit);
		}
		$shuffle = true;
		if (!empty($this->filter['order']) && $this->filter['order']['giaban'] == 'ASC') {
			usort($listSim, array(
				$this,
				'invenAscSort',
			));
			$shuffle = false;
		} elseif (!empty($this->filter['order']) && $this->filter['order']['giaban'] == 'DESC') {
			usort($listSim, array(
				$this,
				'invenDescSort',
			));
			$shuffle = false;
		}
		if ($shuffle) {
			//shuffle($listSim);
		}
		return $listSim;
	}

	function array_find ($needle, $haystack) {
		if (!empty($haystack)) {
			foreach ($haystack as $item) {
				if ($item['sim'] == $needle || $item['simfull'] == $needle) {
					return $item;
				}
			}
		}
	}

	/**
	 * Get shortcode items foreach and get query param
	 * Get data sim from sim filter service getSimByCondition
	 *
	 * @param array $shortCodeItems
	 *
	 * @return array
	 */
	public function getHomepageFilterParamsByShortCode ($shortCodeItems) {
		$widgetParams = [];
		foreach ($shortCodeItems as $index => $item) {
			$customQuery = $this->getCustomQueryBySlug($item->customQuery);
			if ($customQuery != null) {
				$customQueryUrlComponents = parse_url($customQuery);
				parse_str($customQueryUrlComponents['query'], $customQueryParams);
				$widgetParams[$index] = $this->convertCustomQueryParamToPriorityParam($customQueryParams);
			}
			$widgetParams[$index]['currentUrl'] = $item->customSlug;
			$widgetParams[$index]             = array_merge((array) $item, $widgetParams[$index]);
		}
		return $widgetParams;
	}

	public function getSimHotForHome ($domain, $params = []) {

		$cacheSevice = new AppCache(app('cache'), 'getSimHotForHome');
		$keyEn       = serialize($domain);
		$key         = 'homepage.bangsim.' . md5($keyEn);
		if (!empty($params)) {
			if ($cacheSevice->has($key)) {
				$cacheSevice->forget($key);
			}
			$key = 'homepage.bangsim.shortcode.' . md5($keyEn);
		}
		if ($cacheSevice->has($key)) {
			return $cacheSevice->get($key);
		}
		$homePageWidgets = [];
		if (!empty($params)) {
			foreach ($params as $param) {
				$homePageWidgets[] = array_merge((array) $param, ['listSim' => $this->getSimByCondition($param)]);
			}
		} else {
			$arrUt1  = [];
			$dir     = public_path($domain) . DIRECTORY_SEPARATOR;
			$dataAll = [];
			if (file_exists($dir . 'bangsodata.txt')) {
				$dataAll = file_get_contents($dir . 'bangsodata.txt');
				$dataAll = json_decode($dataAll, true);
			}
			// lay data cho mang
			if (file_exists($dir . 'homepage3mang.txt')) {
				$data3mang = file_get_contents($dir . 'homepage3mang.txt');
				$dataUt1   = explode(PHP_EOL, $data3mang);
				if (!empty($dataUt1)) {
					foreach ($dataUt1 as $ut) {
						$ut = str_replace([
							',',
							'.',
						], [
							'',
							'',
						], $ut);
						if (isset($ut[0])) {
							$ut   = $ut[0] == '0' ? $ut : '0' . $ut;
							$ut   = trim($ut);
							$item = $this->array_find($ut, $dataAll);
							if (!empty($item)) {
								$arrUt1[$item['telco']][] = $item;
							}
						}
					}
				}
			}
			if (file_exists($dir . 'simvip3mang.txt')) {
				$data3mang = file_get_contents($dir . 'simvip3mang.txt');
				$dataUt1   = explode(PHP_EOL, $data3mang);
				if (!empty($dataUt1)) {
					foreach ($dataUt1 as $ut) {
						$ut = str_replace([
							',',
							'.',
						], [
							'',
							'',
						], $ut);
						if (isset($ut[0])) {
							$ut   = $ut[0] == '0' ? $ut : '0' . $ut;
							$ut   = trim($ut);
							$item = $this->array_find($ut, $dataAll);
							if (!empty($item)) {
								$arrUt1['0'][] = $item;
							}
						}
					}
				}
			}
			$homePageWidgets = config('global.homepageWidget');
			foreach ($homePageWidgets as $key => $homePageWidget) {
				if (isset($arrUt1[$key])) {
					$homePageWidgets[$key]['listSim'] = $arrUt1[$key];
				}
			}
		}
		$cacheSevice->put($key, $homePageWidgets, 600);
		return $homePageWidgets;
	}
}
