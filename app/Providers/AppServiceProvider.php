<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Domain\DomainInterface;
use Config;
use View;
use Illuminate\Support\Facades\Session;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(DomainInterface $domainIt)
    {
        $app = $this->app;
        $domain = $this->app->request->server('HTTP_HOST');
        $domainInfo = $domainIt->findByDomainName($domain);
        if (!empty($domainInfo)) {
            $hotlineList = [];
            if (!empty($domainInfo->hotline)) {
                $hotline = explode(PHP_EOL, $domainInfo->hotline);
                if (is_array($hotline)) {
                    foreach ($hotline as $key => $hot) {
                        $split = explode('|', $hot);
                        if (isset($split[0]) && isset($split[1]) ) {
                            $hotlineList[$key] = ['hot' => $split[0], 'name' => $split[1]];
                        }
                    }
                }
            }
            $configObj = json_decode($domainInfo->config);
            Config::set('domainInfo', [
                'template' => $domainInfo->template,
                'logo' => $configObj->logo,
                'logo_mobile' => isset($configObj->logo_mobile) ? $configObj->logo_mobile : "",
                'favicon' => $configObj->favicon,
                'main_color' => '#'.$configObj->main_color,
                'domain_name' => $domainInfo->domain_name,
                'home_shortcode' => $domainInfo->home_shortcode,
                'domain' => $domainInfo->domain,
                'header_code' => $domainInfo->header_code,
                'footer_code' => $domainInfo->footer_code,
                'ads_left' => isset($configObj->ads_left) ? $configObj->ads_left : null,
                'ads_right' => isset($configObj->ads_right) ? $configObj->ads_right : null,
                'ads_left_url' => isset($configObj->ads_left_url) ? $configObj->ads_left_url : null,
                'ads_right_url' => isset($configObj->ads_right_url) ? $configObj->ads_right_url : null,
                'hotline_open' => isset($configObj->hotline_open) ? $configObj->hotline_open : null,
                'hotline_close' => isset($configObj->hotline_close) ? $configObj->hotline_close : null,
                'hotlineList' => $hotlineList,
				'condau' => isset($configObj->condau) ? $configObj->condau : null,
                'url_301' => isset($configObj->url_301) ? $configObj->url_301 : null,
                'chat_script' => isset($configObj->chat_script) ? $configObj->chat_script : null,
                'footer_box_1' => isset($configObj->footer_box_1) ? $configObj->footer_box_1 : null,
                'footer_box_2' => isset($configObj->footer_box_2) ? $configObj->footer_box_2 : null,
                'footer_box_3' => isset($configObj->footer_box_3) ? $configObj->footer_box_3 : null,
                'highlights_number' => $domainInfo->highlights_number,
            ]);
            View::share('templateName', isset($domainInfo->template) ? 'templates.' .$domainInfo->template : 'templates.mydang');
        } else {
			Config::set('domainInfo', [
                'template' => 'mydang',
                'logo' => null,
				'logo_mobile' => null,
                'favicon' => null,
                'main_color' => null,
                'domain_name' => 'Sim số đẹp',
                'home_shortcode' => '',
                'domain' => 'onlinetool.pro',
                'header_code' => null,
                'footer_code' => null,
                'ads_left' => null,
                'ads_right' => null,
                'ads_left_url' => null,
                'ads_right_url' => null,
                'hotline_open' => null,
                'hotline_close' => null,
                'hotlineList' => null,
				'condau' => null,
				'url_301' => null,
                'chat_script' => null,
                'footer_box_1' => null,
                'footer_box_2' => null,
                'footer_box_3' => null,
            ]);
            View::share('templateName', 'templates.mydang');
		}
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
