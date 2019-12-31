<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Repositories;
/**
 *
 * @author Killer <laven9696@gmail.com>
 */
interface RepositoriesInterface {
    public function paginate($page = 1, $limit = 10, $all = false);
}
