<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

/**
 * Description of Pages
 *
 * @author Killer <laven9696@gmail.com>
 */
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pages extends Model {

    use Sluggable;
    use SoftDeletes;

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    protected $table = 'tbl_page';

    protected $fillable = ['title', 'detail', 'domain', 'flag_all'];
}
