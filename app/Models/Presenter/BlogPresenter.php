<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Models\Presenter;
/**
 * Description of BlogPresenter
 *
 * @author Administrator
 */
use Laracasts\Presenter\Presenter;

class BlogPresenter extends Presenter{
    public function fullname() {
        return $this->blog_id . '---' . $this->blog_title;
    }
}
