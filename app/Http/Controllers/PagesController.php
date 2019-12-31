<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PagesController extends Controller
{
	public function __construct () {
		parent::__construct();
	}

	public function getHome()
    {
        return view('pages.home');
    }

    public function getAbout()
    {
        return view('pages.about');
    }

    public function getContact()
    {
        return view('pages.contact');
    }

    public function actionDetail($alias, Request $request) {

    }
}
