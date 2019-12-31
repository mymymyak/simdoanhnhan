<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repositories\TraitClass;

/**
 * Description of TraitUploads
 *
 * @author Do Duy Duc <ducdd6647@co-well.com.vn>
 */
trait TraitUploads {

    public $allowExtension = [];
    public $uploadPath = 'uploads/';
    public $denyExtension = ['php',
        'php3',
        'php4',
        'php5',
        'phtml',
        'exe',
        'pl',
        'cgi',
        'html',
        'htm',
        'js',
        'asp',
        'aspx',
        'bat',
        'sh',
        'cmd'
    ];
    public $fileName = '';
    public $fileSize = '';

    /**
     * @var int width
     */
    public $width = 150;

    /**
     * @var int height
     */
    public $height = 150;
    public $temp_path = 'temp/';
    public $maxFileSize = 1024;
    public $upload_errors = array(
        UPLOAD_ERR_OK => "No errors.",
        UPLOAD_ERR_INI_SIZE => "The uploaded file exceeds the upload_max_filesize directive in php.ini",
        UPLOAD_ERR_FORM_SIZE => "Larger than form MAX_FILE_SIZE.",
        UPLOAD_ERR_PARTIAL => "Partial upload.",
        UPLOAD_ERR_NO_FILE => "No file.",
        UPLOAD_ERR_NO_TMP_DIR => "No temporary directory.",
        UPLOAD_ERR_CANT_WRITE => "Can't write to disk.",
        UPLOAD_ERR_EXTENSION => "File upload stopped by extension."
    );

}
