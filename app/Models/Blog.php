<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

/**
 * Description of Blog
 *
 * @author Killer <laven9696@gmail.com>
 */
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

class Blog extends Model {

    protected $table = 'table_blog';
    protected $primaryKey = 'blog_id'; // or null
    protected $fillable = ['blog_title', 'blog_des', 'blog_content', 'blog_img', 'blog_cat_id'];

    use PresentableTrait;
    
    protected $presenter = 'App\Models\Presenter\BlogPresenter';
    /**
     * Get the user's first name.
     *
     * @param  string  $value
     * @return string
     */
    public function getBlogTitleAttribute($value) {
        
        return ucfirst($value);
    }
    
    public function setBlogTitleAttribute($value)
    {
        $this->attributes['blog_title'] = strtoupper($value);
    }
}
