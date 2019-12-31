<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repositories\Blog;

/**
 * Description of BlogRepository
 *
 * @author Killer <laven9696@gmail.com>
 */
use App\Models\Blog;
use App\Repositories\CrudableInterface;
use App\Repositories\Blog\BlogInterface;
use App\Repositories\RepositoryAbstract;
use App\Exceptions\Validation\ValidationException;

class BlogRepository extends RepositoryAbstract implements CrudableInterface, BlogInterface {

    use \App\Repositories\TraitClass\TraitUploads;
    protected $blog;

    protected static $rules = [
        'blog_title' => 'required',
        'blog_des' => 'required',
        'blog_content' => 'required'
    ];

    public function __construct(Blog $blog) {
        $this->blog = $blog;
    }

    public function all() {
        return $this->blog->get();
    }

    public function find($id) {
        return $this->blog->findOrFail($id);
    }

    public function create($attributes) {
        if ($this->isValid($attributes)) {
            $this->blog->fill($attributes)->save();
            
            return true;
        }
        throw new ValidationException('Blog validation failed', $this->getErrors());
    }

    public function update($id, $attributes) {
        $this->blog = $this->find($id);
        if ($this->isValid($attributes)) {
            $this->blog->fill($attributes)->save();
            return true;
        }

        throw new ValidationException('Blog validation failed', $this->getErrors());
    }

    public function delete($id) {
        $this->blog->findOrFail($id)->delete();
    }

    public function paginate($page = 1, $limit = 10, $all = true) {
        
        $result = new \stdClass();
        $result->page = $page;
        $result->limit = $limit;
        $result->items = [];

        $query = $this->blog->orderBy('created_at', 'DESC');

        if (!$all) {
            //$query->where('is_published', 1);
        }
        
        $blogs = $query->skip($limit * ($page -1))->take($limit)->get();
        $result->totalItems = $this->countItem();
        $result->items = $blogs->all();
        return $result;
    }
    protected function countItem() {
        return $this->blog->count();
    }
}
