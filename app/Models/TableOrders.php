<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableOrders extends Model
{

    protected $table = 'tbl_order';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'sosim', 'siminfo', 'hoten', 'diachi', 'dienthoai', 'yeucau',
        'ngaydathang', 'cotrongkho', 'khachquen', 'nguon', 'ip', 'tinhtrang', 'ghichu', 'capnhatcuoi', 'viewed','domain'];
}
