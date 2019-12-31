<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableDomain extends Model
{
    protected $table = 'tbl_domain';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'domain', 'highlights_number', 'active', 'template', 'config', 'domain_name', 'header_code', 'footer_code', 'home_shortcode', 'hotline', 'is_updated_bangso'];
}
