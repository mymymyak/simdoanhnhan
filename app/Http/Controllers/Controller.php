<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct ()
    {
		$this->middleware(function ($request, $next){
			$referer = session('refererList', []);
			if(!is_array($referer) && $referer != ""){
				$referer = [];
				$referer[] = session('refererList', []);
			}
			if(isset($_SERVER["HTTP_REFERER"]) && isset($_SERVER['HTTP_USER_AGENT']))
			{
				$referer[] = date("H:i d/m/Y") . " " . $_SERVER["HTTP_REFERER"];
				$count = count($referer);
				if ($count > 50) {
					$referer = array_shift($referer);
				}
				session()->put('refererList', $referer);
			}
			return $next($request);
		});
    }
}
