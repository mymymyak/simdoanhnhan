<?php
/**
 * Created by PhpStorm.
 * User: DUYDUC
 * Date: 31-Jul-19
 * Time: 10:21 AM
 */

namespace App\ViewComposer;

use Illuminate\View\View;

use App\Repositories\News\NewsInterface;

class NewsComposer
{
    protected $news = null;

    public function __construct(NewsInterface $news)
    {
        $this->news = $news;
    }
    public function compose(View $view)
    {
        $view->with('lastestNews', $this->news->getLastestNews(request()->server('HTTP_HOST')));
    }
}