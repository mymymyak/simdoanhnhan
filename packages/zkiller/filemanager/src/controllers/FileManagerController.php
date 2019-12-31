<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Zkiller\Filemanager\Controllers;

/**
 * Description of FileManagerController
 *
 * @author Do Duy Duc <ducdd6647@co-well.com.vn>
 */
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;

class FileManagerController extends BaseController{
    
    public $folder = null;
    const PERPAGE = 15;
    public function __construct() {
        $this->folder = config('zkiller_upload.folder');
    }
    
    public function getPath($path) {
        return base_path($path);
    }
    public function index(Request $request) {
        $path = $this->getPath($this->folder);
        if (!File::isDirectory($path)) {
            File::makeDirectory($path);
        }
        $page = (int) $request->input('page') ?: 1;
        
        $listFile = collect(File::allFiles($path));

        $slice = $listFile->slice(($page-1)* self::PERPAGE, self::PERPAGE);

        $paginator = new LengthAwarePaginator($slice, $listFile->count(), self::PERPAGE, Paginator::resolveCurrentPage(), [
            'path' => Paginator::resolveCurrentPath()]);
return view('filemanager::index', ['listFile' => $paginator]);
        /*$listPath = File::directories($path);
        foreach($listPath as $path) {
            $files = File::allFiles($path);
            foreach ($files as $file)
            {
                echo (string)$file, "<br>";
            }
        }*/
        
        //return view('filemanager::index', []);
    }
}
