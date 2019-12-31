<?php
/**
 * Created by PhpStorm.
 * User: DUYDUC
 * Date: 15-Jul-19
 * Time: 10:04 AM
 */

namespace App\Repositories\Elastic;

use \stdClass;
use GuzzleHttp\Client;

class ElasticQuery implements ElasticInterface {

	public function searchQuery ($params) {
		$queryBuilder = [];
		$obj          = new \stdClass();
		$page         = isset($params['page']) && (int) $params['page'] > 0 ? $params['page'] : 1;
		$limit        = isset($params['limit']) && (int) $params['limit'] > 0 ? $params['limit'] : config('global.perpageSim');
		$offset       = ($page - 1) * $limit;
		$simSid       = [
			2784,
			290,
			246,
			1994,
			32,
			467,
			2538,
			2704,
			456,
			179,
			1800,
			568,
			2379,
			2556,
			3212,
			307,
		];
		if (isset($params['simSid'])) {
			$simSid = $params['simSid'];
		}
		$elatic_str = '';
		//Từ khóa
		if (!empty($params['keyword'])) {
			if (is_array($params['keyword'])) {
				foreach ($params['keyword'] as $key) {
					$key        = strpos($key, '*') !== false ? $key : '*' . $key;
					$elatic_str .= '{"wildcard": { "sim": "' . $key . '" }},';
				}
			} else {
				$key        = strpos($params['keyword'], '*') !== false ? $params['keyword'] : '*' . $params['keyword'];
				$strSearch  = '{"wildcard": { "sim": "*' . $key . '" }}';
				$elatic_str .= '{ "bool":  {"should": [' . $strSearch . ']}},';
			}
		}
		$elatic_order = '';
		//order
		$elatic_order = '{"giabang": { "order": "asc" } }';
		if (!empty($params['order'])) {
			$orderarr = array();
			foreach ($params['order'] as $k => $v) {
				$orderarr[] = '{"' . $k . '":{"order":"' . strtolower($v) . '"}}';
			}
			$elatic_order = implode(",", $orderarr);
		}
		//Loại sim
		if (!empty($params['cat_ids'])) {
			$elatic_cat = '';
			if (is_numeric($params['cat_ids'])) {
				$elatic_cat = '{"term": { "cat_id": "' . $params['cat_ids'] . '"}}';
				$elatic_str .= '{ "bool":  {"should": [' . $elatic_cat . ']}},';
			} elseif (is_array($params['cat_ids'])) {
				foreach ($params['cat_ids'] as &$value) {
					$elatic_cat .= '{"term": { "cat_id": "' . $value . '"}},';
				}
				$elatic_str .= '{ "bool":  {"should": [' . rtrim($elatic_cat, ',') . ']}},';
			}
		}
		//tránh
		if (!empty($params['tranh'])) {
			$elatic_tranh = '';
			foreach ($params['tranh'] as &$value) {
				$elatic_tranh .= '{"wildcard": { "e": "*' . $value . '*" }},';
			}
			$elatic_str .= '
			{ "bool":  {
			  "must_not": [
				' . rtrim($elatic_tranh, ',') . '
			  ]
			}},';
		}
		//Mạng
		if (!empty($params['telco_id'])) {
			$elatic_mang_ds = '';
			foreach ($params['telco_id'] as $value) {
				if ($value != 0) {
					$elatic_mang_ds .= '{"term": { "telco": "' . $value . '"}},';
				}
			}
			$elatic_str .= '
				{ "bool":  {
				  "should": [
					' . rtrim($elatic_mang_ds, ',') . '
				  ]
				}},';
		}
		if (!empty($params['store_id'])) {
			$elatic_mang_ds = '';
			foreach ($params['store_id'] as $value) {
				if ($value != 0) {
					$elatic_mang_ds .= '{"term": { "store_id": "' . $value . '"}},';
				}
			}
			$elatic_str .= '
				{ "bool":  {
				  "should": [
					' . rtrim($elatic_mang_ds, ',') . '
				  ]
				}},';
		}
		//11 SO VA 10 SO
		if (!empty($params['mm'])) {
			$elatic_str .= '
		{ "bool":  {
          "should": [
			{"wildcard": { "sim": "' . $params['mm'] . '*" }}
          ]
        }},';
		}
		//Đầu số
		if (!empty($params['dauso'])) {
			$elatic_dauso = '';
			if (is_array($params['dauso'])) {
				foreach ($params['dauso'] as $value) {
					$elatic_dauso .= '{"wildcard": { "sim": "' . $value . '*" }},';
				}
				$elatic_str .= '
				{ "bool":  {
				  "should": [
					' . rtrim($elatic_dauso, ',') . '
				  ]
				}},';
			} else {
				$elatic_str .= '
				{ "bool":  {
				  "should": [{"wildcard": { "sim": "' . $params['dauso'] . '*" }}]
				}},';
			}
		}
		//Đầu số
		if (!empty($params['duoiso'])) {
			$elatic_dauso = '';
			if (is_array($params['duoiso'])) {
				foreach ($params['duoiso'] as $value) {
					$elatic_dauso .= '{"wildcard": { "sim": "*' . $value . '" }},';
				}
				$elatic_str .= '
				{ "bool":  {
				  "should": [
					' . rtrim($elatic_dauso, ',') . '
				  ]
				}},';
			} else {
				$elatic_str .= '
				{ "bool":  {
				  "should": [{"wildcard": { "sim": "*' . $params['duoiso'] . '" }}]
				}},';
			}
		}
		if (!empty($params['dausoco'])) {
			$elatic_str .= '
		{ "bool":  {
          "should": [
            {"wildcard": { "sim": "0912*" }},
			{"wildcard": { "sim": "0913*" }},
			{"wildcard": { "sim": "0918*" }},
			{"wildcard": { "sim": "0919*" }},
			{"wildcard": { "sim": "0902*" }},
			{"wildcard": { "sim": "0908*" }},
			{"wildcard": { "sim": "0909*" }},
			{"wildcard": { "sim": "0982*" }},
			{"wildcard": { "sim": "0988*" }},
			{"wildcard": { "sim": "0989*" }}
          ]
        }},';
		}
		$minPrice   = !empty($params['minPrice']) ? (int) $params['minPrice'] : 0;
		$maxPrice   = !empty($params['maxPrice']) ? (int) $params['maxPrice'] : 0;
		$strDefault = '';
		if ($minPrice == 0 && $maxPrice == 0) {
			/*if (!empty($loc->simSid)) {
				$defaultPrice = $loc->simSidPrice != 0 ? $loc->simSidPrice : 5000000;
				$strDefault = '"must": [{"terms":{"s2":['.implode(',', $loc->simSid).']}},{"range":{"pn":{"lt":"'.$defaultPrice.'"}}}],';
			}*/
		} else if ($maxPrice == 0) {
			$elatic_str .= '{"range":{"giaban":{"from":"' . $minPrice . '"}}},';
		} else if ($minPrice == 0) {
			$elatic_str .= '{"range":{"giaban":{"to":"' . $maxPrice . '"}}},';
		} else {
			$elatic_str .= '{"range":{"giaban":{"from":"' . $minPrice . '","to":"' . $maxPrice . '"}}},';
		}
		if (empty($params['rmS3']) && !empty($params['simSid'])) {
			$strDefault = ',{"terms":{"s3":[' . implode(',', $params['simSid']) . ']}}';
		}
		$elatic_query = rtrim($elatic_str, ',');
		if ($elatic_query == "") {
			$elatic_query = '{"match_all":{}}';
		}
		$time30 = strtotime('-30 day');
		//2290,2626,2192,2830,1899,2513,2428,3054,2484,3144,2039,1917,2379,2321,2344,2427,2449,2572
		$elatic_anmakho = '';
		if (isset($params['anmakho']) && $params['anmakho'] !== "") {
			$makho = explode(",", $params['anmakho']);
			if (is_array($makho)) {
				foreach ($makho as $value) {
					$elatic_anmakho .= '{"term": { "sim.store_id": "' . trim($value) . '" } },';
				}
			}
		}
		if ($elatic_anmakho != '') {
			$elatic_anmakho = ',' . $elatic_anmakho;
		}
		$elastr = '{
		  "query": {
			"bool": {
			  "must": [
				' . $elatic_query . ',
				{"range": {"l.sec": {"gt": "' . $time30 . '"}}}
				' . $strDefault . '
			  ],
			  "must_not":[{ "term": { "sim.daban": "true" } },{ "term": { "h": "true" }}' . rtrim($elatic_anmakho, ",") . ']
			}
		  },
		  "from": ' . $offset . ',
		  "size": ' . $limit . ',
		  "sort": [' . $elatic_order . ']
		}';
		$elastr = str_replace("mm", "m", $elastr);
		$elastr = str_replace("daban", "d", $elastr);
		$elastr = str_replace("giabang", "pn", $elastr);
		$elastr = str_replace("giaban", "pn", $elastr);
		$elastr = str_replace('sim.', '', $elastr);
		$elastr = str_replace('"sim"', '"i"', $elastr);
		$elastr = str_replace('telco', 't', $elastr);
		$elastr = str_replace('cat_id', 'c', $elastr);
		$elastr = str_replace('store_id', 's3', $elastr);
		$elastr = str_replace('priority', 'ut', $elastr);
		//echo $elastr;die;
		return $elastr;
	}

	public function search ($params) {
		$jsonSearch = $this->searchQuery($params);
		$client     = new Client(['headers' => ['Content-Type' => 'application/json']]);
		// $response = $client->post(env('SIM_API') . '/khoso/sim/_search',
		// ['body' => $jsonSearch]
		// );
		$response = $client->get('https://static.simthanglong.vn/db/search.php?d=1&s=' . urlencode($jsonSearch));
		if ($response->getStatusCode() == 200) {
			$content       = $response->getBody()->getContents();
			$content       = json_decode($content, true);
			$arr           = $content['hits']['hits'];
			$responseArray = [];
			$total         = $content['hits']['total'];
			foreach ($arr as $key => $value) {
				$responseArray[] = array(
					'id'      => $value['_source']['i'],
					'sim'     => $value['_source']['i'],
					'simfull' => $value['_source']['f'],
					'price'   => $value['_source']['pn'],
					'cat_id'  => $value['_source']['c2'],
					'telco'   => $value['_source']['t'],
					'd'       => $value['_source']['d'],
					'h'       => $value['_source']['h'],
					'd2'      => $value['_source']['d2'],
				);
			}
			return [
				'total' => $total,
				'list'  => $responseArray,
			];
		}
		return [];
	}

	public function searchByTerms ($listPhoneNumber, $limit) {
		// TODO: Implement searchByTerm() method.
		if (!empty($listPhoneNumber)) {
			$listPhoneString    = implode('","', $listPhoneNumber);
			$elasticQueryString = '{
		  "query": {
			"bool": {"must":[{ "terms": { "i.keyword": ["' . $listPhoneString . '"] }}' . ']
			}
		  },
		  "from": 0,
		  "size": ' . $limit . ',
		  "sort": [],
		  "aggs": {}
		}';
			$client             = new Client(['headers' => ['Content-Type' => 'application/json']]);
			$response           = $client->get('https://static.simthanglong.vn/db/search.php?d=1&s=' . urlencode($elasticQueryString));
			if ($response->getStatusCode() == 200) {
				$content = $response->getBody()->getContents();
				$content = json_decode($content, true);
				$listSimResponse = [];
				if (isset($content['hits']) && isset($content['hits']['hits'])) {
					$numbersInfo = $content['hits']['hits'];
					foreach ($numbersInfo as $numberInfo){
						$listSimResponse[] = $this->responseParser($numberInfo);
					}
					return $listSimResponse;
				}
			}
		}
		return [];
	}

	private function responseParser (array $response) {
		$responseParser = [
			'id'      => $response['_source']['i'],
			'sim'     => $response['_source']['i'],
			'simfull' => $response['_source']['f'],
			'price'   => $response['_source']['pn'],
			'cat_id'  => $response['_source']['c2'],
			'telco'   => $response['_source']['t'],
			'd'       => $response['_source']['d'],
			'h'       => $response['_source']['h'],
			'd2'      => $response['_source']['d2'],
		];
		if (!empty($response['_source']['s'])) {
			$sellers = [];
			foreach ($response['_source']['s'] as $index => $extraInformation) {
				$sellers[] = [
					'seller_id'       => $extraInformation['id'],
					'price'           => $extraInformation['pb'],
					'commission'      => $extraInformation['pg'],
					'sim_last_update' => (isset($extraInformation['l']) && is_array($extraInformation['l'])) ? $extraInformation['l']['sec'] : "",
				];
			}
			$responseParser['sellers'] = $sellers;
		}
		return $responseParser;
	}
}
