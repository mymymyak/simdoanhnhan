<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Repositories\User;
/**
 * Description of RoleRepository
 *
 * @author Do Duy Duc <ducdd6647@co-well.com.vn>
 */
use App\Repositories\CrudableInterface;
use App\Repositories\RepositoryAbstract;
use App\Exceptions\Validation\ValidationException;
use Sentinel;
use Illuminate\Support\Facades\Route;

class RoleRepository extends RepositoryAbstract implements RoleInterface, CrudableInterface{
    
    protected $role;

    protected static $rules = [
        'slug' => 'required',
        'name' => 'required',
    ];
    
    public function __construct() {
        $this->role = Sentinel::getRoleRepository()->createModel();;
    }

    public function all() {
        return $this->role->get();
    }

    public function find($id) {
        return $this->role->findOrFail($id);
    }

    public function create($attributes) {
        if ($this->isValid($attributes)) {
            $this->role->fill($attributes)->save();
            
            return true;
        }
        throw new ValidationException('Role validation failed', $this->getErrors());
    }

    public function update($id, $attributes) {
        $this->role = $this->find($id);
        $this->role->permissions = $this->convertPermission($attributes['permissions']);
        if ($this->isValid($attributes)) {
            $this->role->name = htmlentities($attributes['name']);
            $this->role->slug = htmlentities($attributes['slug']);
            $this->role->save();
            return true;
        }

        throw new ValidationException('Role validation failed', $this->getErrors());
    }

    public function delete($id) {
        $this->role->findOrFail($id)->delete();
    }

    public function paginate($page = 1, $limit = 10, $all = true) {
        $result = new \stdClass();
        $result->page = $page;
        $result->limit = $limit;
        $result->items = [];

        $query = $this->role->orderBy('created_at', 'DESC');

        if (!$all) {
            //$query->where('is_published', 1);
        }

        $blogs = $query->skip($limit * ($page -1))->take($limit)->get();
        $result->totalItems = $this->countItem();
        $result->items = $blogs->all();
        return $result;
    }
    public function paginateSimple($limit, $all = true) {
        $query = $this->role->orderBy('created_at', 'DESC');
        if (!$all) {
            //$query->where('is_published', 1);
        }
        return $blogs = $query->paginate($limit);
    }
    protected function countItem() {
        return $this->role->count();
    }
    
    public function getAllRoute() {
        $routeCollection = Route::getRoutes();
        $routeList = [];
        foreach ($routeCollection as $value) {
            if (!empty($value->getName())) {
                $routeList[] = $value->getName();
            }
        }
        return $routeList;
    }
    public function convertPermission($arrayData) {
        if (!empty($arrayData)) {
            foreach ($arrayData as $key => $val) {
                $permission[trim($val)] = true;
            }
        }
        return $permission;
    }
}
