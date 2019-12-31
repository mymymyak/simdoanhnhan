<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repositories\Bangso;
/**
 * Description of BangsoRepository
 *
 * @author ducdd6647<ducdd6647@co-well.com.vn>
 */

use App\Repositories\RepositoryAbstract;
use App\Exceptions\Validation\ValidationException;
use Faker\Provider\File;
use App\Events\SaveBangso;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BangsoRepository extends RepositoryAbstract {

	protected        $bangso;

	protected        $domain         = null;

	protected        $bangsoPath     = '';

	protected        $bangsoJsonPath = '';

	protected static $rules          = [
		'domain' => 'required',
	];

	protected static $rulesDanhmuc   = [
		'domain'   => 'required',
		'danh_muc' => 'required',
	];

	public function __construct () {
		$this->domain = isset($_COOKIE['domain_setting']) ? $_COOKIE['domain_setting'] : null;
		if (!empty($this->domain)) {
			$this->bangsoPath     = public_path($this->domain) . '/bangso.txt';
			$this->bangsoJsonPath = public_path($this->domain) . '/bangsodata.txt';
		}
	}

	public function setDomain($domain){
		$this->domain = $domain;
	}

	public function setBangSoPath(){
		$this->bangsoPath     = public_path($this->domain) . '/bangso.txt';
		$this->bangsoJsonPath = public_path($this->domain) . '/bangsodata.txt';
	}
	public function find () {
		if (file_exists($this->bangsoJsonPath)) {
			$listSim = json_decode(file_get_contents($this->bangsoJsonPath), true);
			$content = '';
			foreach ($listSim as $i => $v) {
				$content .= $v['simfull'] . "\t" . $v['price'] . "\r\n";
			}
			return $content;
		}
		return '';
	}

	public function getBangsoTong ($domain) {
		$bangsoUrl = public_path($domain) . '/bangsodata.txt';
		if (file_exists($bangsoUrl)) {
			return json_decode(file_get_contents($bangsoUrl), true);
		}
		return [];
	}

	public function findBangsotrangchu () {
		$path = public_path($this->domain) . '/homepage3mang.txt';
		if (file_exists($path)) {
			return file_get_contents($path);
		}
		return '';
	}

	public function findCustomQuery () {
		$path = public_path($this->domain) . '/custom_query.txt';
		if (file_exists($path)) {
			return file_get_contents($path);
		}
		return '';
	}

	public function saveCustomQuery ($attributes) {
		if (!file_exists(public_path($this->domain))) {
			mkdir(public_path($this->domain), 0777);
		}
		$path = public_path($this->domain) . '/custom_query.txt';
		file_put_contents($path, $attributes['customQuery']);
		return true;
	}


	public function findCustomQueryConfiguration(){
		$path = public_path($this->domain) . '/custom_query_configuration.txt';
		if (file_exists($path)) {
			return file_get_contents($path);
		}
		return '';
	}

	public function saveCustomQueryConfiguration ($attributes) {
		if (!file_exists(public_path($this->domain))) {
			mkdir(public_path($this->domain), 0777);
		}
		$path = public_path($this->domain) . '/custom_query_configuration.txt';
		file_put_contents($path, $attributes['customQueryConfiguration']);
		return true;
	}

	public function getBangsoDetail ($attributes) {
		if (!empty($attributes['danh_muc']) && file_exists(public_path($this->domain) . '/' . $attributes['danh_muc'] . '.txt')) {
			return file_get_contents(public_path($this->domain) . '/' . $attributes['danh_muc'] . '.txt');
		}
		return '';
	}

	public function saveDanhmuc ($attributes) {
		$attributes['domain'] = $this->domain;
		if ($this->isValid($attributes, static::$rulesDanhmuc)) {
			$danhmucPath = public_path($this->domain) . DIRECTORY_SEPARATOR . $attributes['danh_muc'] . '.txt';
			if (!file_exists(public_path($this->domain))) {
				mkdir(public_path($this->domain), 0777);
			}
			file_put_contents($danhmucPath, $attributes['bangso']);
			return true;
		}
		throw new ValidationException('Bảng số validation failed', $this->getErrors());
	}

	public function saveBangsoTrangchu ($attributes) {
		$attributes['domain'] = $this->domain;
		if ($this->isValid($attributes)) {
			$path = public_path($this->domain) . '/homepage3mang.txt';
			if (!file_exists(public_path($this->domain))) {
				mkdir(public_path($this->domain), 0777);
			}
			file_put_contents($path, $attributes['bangso']);
			return true;
		}
		throw new ValidationException('Bảng số validation failed', $this->getErrors());
	}

	public function save ($attributes) {
		$attributes['domain'] = $this->domain;
		if ($this->isValid($attributes)) {
			if (!file_exists(public_path($this->domain))) {
				mkdir(public_path($this->domain), 0777);
			}
			file_put_contents($this->bangsoPath, $attributes['bangso']);
			$this->createJson($attributes['bangso']);
			return true;
		}
		throw new ValidationException('Bảng số validation failed', $this->getErrors());
	}

	public function createJson ($bangso) {
		$listSim     = [];
		$bangSoLines = explode("\n", trim($bangso));
		if (!empty($bangSoLines)) {
			foreach ($bangSoLines as $line) {
				$line = preg_split('/\s+/', $line);
				if (empty($line[1])) {
					break;
				}
				$gia = trim($line[1]);
				if (empty($gia)) {
					continue;
				}
				$sosim     = trim($line[0]);
				$sosim     = $line[0][0] != '0' ? '0' . $sosim : $sosim;
				$sosimFull = str_replace([
					',',
					' ',
				], [
					'',
					'',
				], $sosim);
				$sosim     = str_replace([
					'.',
					' ',
					',',
				], [
					'',
					'',
					'',
				], $sosim);
				$price     = str_replace([
					',',
					'.',
				], [
					'',
					'',
				], $gia);
				$idloai    = get_cat_id($sosim);
				$mang      = checkmang($sosim);
				$idmang    = convertmang($mang);
				$listSim[] = [
					'sim'     => $sosim,
					'simfull' => $sosimFull,
					'price'   => $price,
					'cat_id'  => $idloai,
					'telco'   => $idmang,
				];
			}
		}
		file_put_contents($this->bangsoJsonPath, json_encode($listSim));
	}

	public function saveLinkGoiY ($attributes) {
		$path = public_path($this->domain) . '/linkgoiy.txt';
		if (!file_exists(public_path($this->domain))) {
			mkdir(public_path($this->domain), 0777);
		}
		file_put_contents($path, $attributes['linkgoiy']);
		return true;
	}

	public function getLinkGoiY () {
		$path = public_path($this->domain) . '/linkgoiy.txt';
		if (file_exists($path)) {
			return file_get_contents($path);
		}
		return '';
	}

	/**
	 * @param UploadedFile $file
	 * @param string       $fileName
	 *
	 * @return string
	 */
	public function uploadSitemapAndRobot (UploadedFile $file, $fileName) {
		$path = public_path($this->domain);
		try {
			if (!is_dir($path)) {
				mkdir($path, 0777);
			}
			$file->move($path, $fileName);
			return true;
		} catch (\Exception $exception) {
			throw new BadRequestHttpException($exception->getMessage());
		}
	}

	/**
	 * @param string $fileName
	 *
	 * @return false|string
	 */
	public function readSitemapAndRobots ($fileName) {
		$path = public_path($this->domain).'/'.$fileName;
		if(!file_exists($path)){
			throw new NotFoundHttpException();
		}
		return file_get_contents($path);
	}
}
