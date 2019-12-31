<?php
/**
 * Created by PhpStorm.
 * User: DUYDUC
 * Date: 31-Jul-19
 * Time: 10:21 AM
 */

namespace App\ViewComposer;

use Illuminate\View\View;


class SiteComposer
{
    public function __construct()
    {

    }
    public function compose(View $view)
    {
        $view->with('baseUrl', '');
    }
}