<?php

namespace App\Http\Controllers;

use App\Repositories\Elastic\ElasticInterface;
use App\Repositories\Options\OptionsInterface;
use App\Repositories\Pages\PagesInterface;
use App\Services\SimFilter;
use View;
use Illuminate\Http\Request;
use App\Repositories\News\NewsInterface;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $_news;
    protected $_pages;
    protected $template;
	protected $filterService;
	protected $options;
	protected $domainName;

	public function __construct (NewsInterface $news, PagesInterface $pages,
		ElasticInterface $elastic, OptionsInterface $options, Request $request)
    {
        $this->_news = $news;
        $this->_pages = $pages;
        $this->domain = $request->server('HTTP_HOST');
        $this->template = isset(config('domainInfo')['template']) ? 'templates.' . config('domainInfo')['template'] .'.' : 'templates.default.';
	    $this->filterService = new SimFilter($elastic);
	    $this->options = $options;
	    $this->domainName = config('domainInfo')['domain_name'];
    }

    public function actionDetailList(Request $request) {
        $news = $this->_news->paginateSimple(12);
        
        View::composer('*', function ($view) {
            $view->with('web_title', 'Tin Tức Sim Số');
            $view->with('web_h1', 'Tin Tức Sim Số');
            $view->with('web_description', 'Tin Tức Sim Số');
            $view->with('web_head', '');
            $view->with('web_foot', '');
        });

        return view($this->template . 'news.list', [
            'news' => $news
        ]);
    }

    public function actionDetailPage($alias) {
        $pages = $this->_pages->findByAlias($alias, $this->domain);
        if (empty($pages)) {
            return redirect()->home();
        }
        View::composer('*', function ($view) use ($pages) {
            $view->with('web_title', $pages->title);
            $view->with('web_h1', $pages->title);
            $view->with('web_description', substr_word(strip_tags($pages->detail), 160));
            $view->with('web_head', '');
            $view->with('web_foot', '');
        });

        return view($this->template . 'pages.detail', [
            'pages' => $pages
        ]);
    }

    public function actionDetailNew($alias, $id) {
        $news = $this->_news->find($id);
        if (empty($news)) {
            return redirect()->home();
        }
        View::composer('*', function ($view) use ($news) {
            $view->with('web_title', $news->title);
            $view->with('web_h1', $news->title);
            $view->with('web_description', $news->description);
            $view->with('web_head', '');
            $view->with('web_foot', '');
        });
		$related = $this->_news->getRelatedNews($this->domain, $news->id);
        return view($this->template.'news.detail', [
            'news' => $news,
			'related' => $related
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $news = $this->_news->paginateSimple(5);

        return view('home', [
            'news' => $news
        ]);
    }

    public function notFound(){
	    $listUuTien = $this->filterService->getSimHotForHome($this->domain);
	    $keyHot = 'listSimHot'.md5('trang-chu');
	    $listSimHot = session()->get($keyHot);

	    $options = $this->options->findByOptionNameAndDomain('trang-chu',$this->domain);
	    $seoConfig = null;
	    if (!empty($options)) {
		    $seoConfig = json_decode($options->option_value);
	    }
	    View::composer('*', function ($view) use ($seoConfig) {
		    $view->with('web_title', !empty($seoConfig->title) ? $seoConfig->title : 'Sim số đẹp giá rẻ - ' . $this->domainName);
		    $view->with('web_h1', !empty($seoConfig->h1) ? $seoConfig->h1 : 'Sim số đẹp');
		    $view->with('web_description', !empty($seoConfig->description) ? $seoConfig->description : 'Sim số đẹp giá rẻ - ' . $this->domainName);
		    $view->with('web_head', !empty($seoConfig->head) ? $seoConfig->head : '');
		    $view->with('web_foot', !empty($seoConfig->foot) ? $seoConfig->foot : '');
	    });
	    return view($this->template . 'errors.404', [
		    'listUuTien' => $listUuTien,
		    'listSimHot' => $listSimHot
	    ]);
    }
}
