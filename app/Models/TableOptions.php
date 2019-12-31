<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableOptions extends Model
{
    protected $table = 'tbl_options';

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
    protected $fillable = ['id', 'option_name', 'option_value', 'type', 'domain'];
}
