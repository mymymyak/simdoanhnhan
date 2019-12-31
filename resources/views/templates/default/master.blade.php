<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="vi" xml:lang="vi">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
	@if(request()->get('page', 1) > 1)<meta name="robots" content="noindex, follow">@else<meta name="robots" content="index, follow">@endif
    <title>@if(!empty($web_title)){{$web_title}}@else @yield('title')@endif</title>
    @if(!empty($web_description))<meta name="description" content="{{$web_description}}">@endif
    <meta name="theme-color" content="#259D99"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<?php
	$http = 'http://';
	if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
		$http = 'https://';
	}
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
		$http = 'https://';
	}
	$canonical = request()->path() == '/' ? $http . request()->server('HTTP_HOST') : request()->url() ?>
	<link rel="canonical" href="{{ $canonical }}" />
    <!-- Mobile Specific Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ $http . request()->server('HTTP_HOST') }}/frontend/css/main.css">
    <link rel="stylesheet" href="{{ $http . request()->server('HTTP_HOST') }}/frontend/css/responsive.css">
    <link href="{!! !empty(config('domainInfo')['favicon']) ? config('domainInfo')['favicon'] : url('frontend/icon/favicon.png') !!}" type="image/x-icon" rel="shortcut icon">
    <link href="https://fonts.googleapis.com/css?family=Muli:400,600&display=swap&subset=vietnamese" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.public/js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="{{ $http . request()->server('HTTP_HOST') }}/frontend/js/jquery.min.js"></script>
    <script src="{{ $http . request()->server('HTTP_HOST') }}/frontend/js/popper.min.js"></script>
	<script> var qazy_image = "/frontend/icon/bar.gif";  </script>
    <script src="{{ $http . request()->server('HTTP_HOST') }}/frontend/js/qazy.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="{{ $http . request()->server('HTTP_HOST') }}/frontend/js/main.js?v=10"></script>
    <base href="{{$baseUrl}}/" />
    {!! !empty(config('domainInfo')['header_code']) ? config('domainInfo')['header_code'] : '' !!}
    @if(!empty(config('domainInfo')['main_color']))
        @php $mainColor = config('domainInfo')['main_color']; @endphp
    <style type="text/css">
        header{background: {{$mainColor}};}
        .panel-orange.panel-arrows .panel-header{color: {{$mainColor}};}
        .divTableRow .divTableCell .order-btn{color:{{$mainColor}}}
        .divTableRow:hover .order-btn{background:{{$mainColor}}}
        .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover{
            background-color: {{$mainColor}};
            border-color: {{$mainColor}};
        }
        .divTableRow:hover .divTableCell .order-btn{
            color:#fff;
        }
        .pagination>li>a, .pagination>li>span{color: {{$mainColor}}}
        ul.sidebar-filter a:before{
            background: {{$mainColor}};
        }
		.sim-header,.related-title{
			color: {{$mainColor}};
		}
		footer .hotline-ft.arrow_box{
			background:{{$mainColor}}
		}
		footer .hotline-ft.arrow_box:after{
			border-top-color: {{$mainColor}};
		}
		.simhot .simhotitem.viewmore{
			background:{{$mainColor}}
		}
		.pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover{z-index:1}
		@media(max-width:768px){}
		.box-filter{margin-bottom:5px;}
		.seo-box-hotline{background:#fff;padding:15px;}

		.search-groups span a img {
			margin-top: 0
		}

        .logo-header {
            background: url({{config('domainInfo')['logo_mobile'] != null
                    ? config('domainInfo')['logo_mobile'] : "frontend/icon/logo_mobile.png" }}) no-repeat;
            height: 35px;
            width: 35px;
            display: inline-block;
            background-size: contain
        }
    </style>
    @endif
    <script type="application/ld+json">
    <?php  $hotline = getHotLine(config('domainInfo')['hotlineList']);?>
        {
            "@context":"https://schema.org",
            "@type":"LocalBusiness",
            "name":"@if(!empty($web_title)){{$web_title}}@else @yield('title')@endif",
            "image":"{!! !empty(config('domainInfo')['logo']) ? config('domainInfo')['logo'] : url('frontend/icon/logo.png') !!}",
            "@id":"",
            "url":"{{ $canonical }}",
            "telephone":"{{$hotline['hot']}}",
            "priceRange":"299000, 10000000000",
            "address":{
               "@type":"PostalAddress",
               "streetAddress":"22 Thành Công, Ba Đình",
               "addressLocality":"Hà Nội",
               "postalCode":"100000",
               "addressCountry":"VN",
               "geo":{
                    "@type":"GeoCoordinates",
                    "latitude":21.0191469,
                    "longitude":105.82817,
                    "openingHoursSpecification":{
                        "@type":"OpeningHoursSpecification",
                        "dayOfWeek":["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],
                        "opens":"@if((config('domainInfo')['hotline_open'])){{config('domainInfo')['hotline_open'].":00"}}@else{{"00:00"}}@endif",
                        "closes":"@if((config('domainInfo')['hotline_open'])){{config('domainInfo')['hotline_close'.":00"]}}@else{{"23:59"}}@endif"
                    },
                   "sameAs":[
                       "https://www.facebook.com/simgiaresimdoanhnhan/"
                   ]
               }
            }
        }

    </script>
</head>
<body>
    <header>

        <div class="container">
            <div class="row">
                <a href="/"><span class="header-logo"><i class="logo-header"></i></span></a>
                <span class="btn-open-menu"><i class="icon-open-menu"></i></span>
                <div class="search-groups">
                    <span><a href="/"><img src="{!! !empty(config('domainInfo')['logo']) ? config('domainInfo')['logo'] : 'frontend/icon/logo.png' !!}" style="height: 60px" class="img-logo-header" /></a></span>
                    <form id="timkiem" name="timkiem" method="get" action="/">
                        <div class="form-search">
                            <input type="tel" autocomplete="off" id="search" name="search" value="" placeholder="Bạn tìm sim gì hôm nay" />
                            <div class="search-popover popover bottom">
                                <div class="arrow"></div>
                                <h3 class="popover-title">Hướng dẫn tìm sim</h3>
                                <div class="popover-content">
                                    - Sử dụng dấu <span class="red">*</span> đại điện cho một chuỗi số. <br>
                                    - Để tìm sim bắt đầu bằng 098, quý khách nhập vào 098*<br>
                                    - Để tìm sim kết thúc bằng 888, quý khách nhập vào *888<br>
                                    - Để tìm sim bắt đầu bằng 098 và kết thúc bằng 888, nhập vào 098*888<br>
                                    - Để tìm sim bên trong có số 888, nhập vào 888<br>
                                </div>
                            </div>
                            <input type="image" id="imagesubmit" alt="Login" src="/frontend/images/search_icon.png" />
                        </div>
                    </form>
                </div>
                <div class="header-menu">
                    <ul>
                        <li class="has-child">
                            <a href="/" class="hd-icon icon sd">Danh mục sim</a>
                            <ul class="dr-item">
                                <li><a href="/sim-vip">Sim VIP</a></li>
                                <li><a href="/sim-luc-quy">Sim Lục quý</a></li>
                                <li><a href="/sim-luc-quy-giua">Sim Lục quý giữa</a></li>
                                <li><a href="/sim-ngu-quy">Sim ngũ quý</a></li>
                                <li><a href="/sim-ngu-quy-giua">Sim ngũ quý giữa</a></li>
                                <li><a href="/sim-tu-quy">Sim Tứ quý</a></li>
                                <li><a href="/sim-tu-quy-giua">Sim tứ quý giữa</a></li>
                                <li><a href="/sim-tam-hoa">Sim Tam hoa</a></li>
                                <li><a href="/sim-tam-hoa-kep">Sim tam hoa kép</a></li>
                                <li><a href="/sim-taxi">Sim Taxi</a></li>
                                <li><a href="/sim-loc-phat">Sim Lộc phát</a></li>
                                <li><a href="/sim-than-tai">Sim Thần tài</a></li>
                                <li><a href="/sim-ong-dia">Sim Ông địa</a></li>
                                <li><a href="/sim-lap-kep">Sim Lặp kép</a></li>
                                <li><a href="/sim-ganh-dao">Sim Gánh đảo</a></li>
                                <li><a href="/sim-tien-len">Sim Tiến lên</a></li>
                                <li><a href="/sim-de-nho">Sim Dễ nhớ</a></li>
                                <li><a href="/sim-nam-sinh">Sim Năm sinh</a></li>
                                <li><a href="/sim-so-doc">Sim Số độc</a></li>
                                <li><a href="/sim-dau-so-co">Sim đầu số cổ</a></li>
                                <li><a href="/sim-tra-gop">Sim Trả Góp</a></li>
                            </ul>
                        </li>
                        <li><a href="/sim-viettel" class="hd-icon icon vt">Viettel</a></li>
                        <li><a href="/sim-vinaphone" class="hd-icon icon vn">VinaPhone</a></li>
                        <li><a href="/sim-mobifone" class="hd-icon icon mb2">Mobifone</a></li>
                        <li><a href="/sim-gmobile" class="hd-icon icon gb">Gmobile</a></li>
                        <li><a href="{{route('frontend.news.list')}}" class="hd-icon icon iinfo">Kiến thức sim</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <section id="main-content">
        <div class="container">
            <div class="row">
                <section class="content">
                    @yield('content')
                    <div class="hotlinebottom" id="hlbt">
                        <a class="hotline" href="tel:{{$hotline['hot']}}">
                            <div id="callnumber"><span>&nbsp;</span>
                            </div>
                            <div>
                            </div>{{$hotline['hot']}}
                        </a>
                    </div>
                </section>
                <aside class="sidebar">
                    <div class="panel panel-arrows panel-orange panel-mang">
                        <div class="panel-header">
                            <span>SIM THEO GIÁ</span>
                        </div>
                        <div class="panel-content">
                            <ul class="sidebar-filter price_filter" data-name="price_filter">
                                <li><a href="/sim-gia-duoi-500-nghin">Dưới 500 nghìn</a></li>
                                <li><a href="/sim-gia-500-nghin-den-1-trieu">Sim 500 - 1 triệu</a></li>
                                <li><a href="/sim-gia-1-trieu-den-3-trieu">Sim 1 - 3 triệu</a></li>
                                <li><a href="/sim-gia-3-trieu-den-5-trieu">Sim 3 - 5 triệu</a></li>
                                <li><a href="/sim-gia-5-trieu-den-10-trieu">Sim 5 - 10 triệu</a></li>
                                <li><a href="/sim-gia-10-trieu-den-50-trieu">Sim 10 - 50 triệu</a></li>
                                <li><a href="/sim-gia-50-trieu-den-100-trieu">Sim 50 - 100 triệu</a></li>
                                <li><a href="/sim-gia-100-trieu-den-200-trieu">Sim 100 - 200 triệu</a></li>
                                <li><a href="/sim-gia-tren-200-trieu">Sim trên 200 triệu</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel panel-arrows panel-orange panel-loai">
                        <div class="panel-header">
                            <span>SIM THEO MẠNG</span>
                        </div>
                        <div class="panel-content">
                            <ul class="sidebar-filter">
                                <li><a href="/sim-viettel">Sim Viettel</a></li>
                                <li><a href="/sim-vinaphone">Sim Vinaphone</a></li>
                                <li><a href="/sim-mobifone">Sim Mobifone</a></li>
                                <li><a href="/sim-vietnamobile">Sim Vietnamobile</a></li>
                                <li><a href="/sim-gmobile">Sim Gmobile</a></li>
                                <li><a href="/sim-itelecom">Sim Itelecom</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel panel-arrows panel-orange panel-loai">
                        <div class="panel-header">
                            <span>SIM THEO LOẠI</span>
                        </div>
                        <div class="panel-content">
                            <ul class="sidebar-filter cat_filter" data-name="cat_filter">
                                <li><a href="/sim-vip">Sim VIP</a></li>
                                <li><a href="/sim-luc-quy">Sim Lục quý</a></li>
                                <li><a href="/sim-luc-quy-giua">Sim Lục quý giữa</a></li>
                                <li><a href="/sim-ngu-quy">Sim ngũ quý</a></li>
                                <li><a href="/sim-ngu-quy-giua">Sim ngũ quý giữa</a></li>
                                <li><a href="/sim-tu-quy">Sim Tứ quý</a></li>
                                <li><a href="/sim-tu-quy-giua">Sim tứ quý giữa</a></li>
                                <li><a href="/sim-tam-hoa">Sim Tam hoa</a></li>
                                <li><a href="/sim-tam-hoa-kep">Sim tam hoa kép</a></li>
                                <li><a href="/sim-taxi">Sim Taxi</a></li>
                                <li><a href="/sim-loc-phat">Sim Lộc phát</a></li>
                                <li><a href="/sim-than-tai">Sim Thần tài</a></li>
                                <li><a href="/sim-ong-dia">Sim Ông địa</a></li>
                                <li><a href="/sim-lap-kep">Sim Lặp kép</a></li>
                                <li><a href="/sim-ganh-dao">Sim Gánh đảo</a></li>
                                <li><a href="/sim-tien-len">Sim Tiến lên</a></li>
                                <li><a href="/sim-de-nho">Sim Dễ nhớ</a></li>
                                <li><a href="/sim-nam-sinh">Sim Năm sinh</a></li>
                                <li><a href="/sim-so-doc">Sim Số độc</a></li>
                                <li><a href="/sim-dau-so-co">Sim đầu số cổ</a></li>
                                <li><a href="/sim-tu-chon">Sim Tự chọn</a></li>
                                <li><a href="/sim-tra-gop">Sim Trả góp</a></li>
                                <li><a href="/sim-gia-re">Sim Giá rẻ</a></li>
                            </ul>
                        </div>
                    </div>
                    @if ($lastestOrder->count())
                    <div class="panel panel-arrows panel-orange hidden-xs">
                        <div class="panel-header">
                            <span>ĐƠN HÀNG MỚI</span>
                        </div>
                        <div class="panel-content">
                            <marquee direction="up" width="185" onmouseover="this.stop()" scrolldelay="1" scrollamount="2" onmouseout="this.start()" height="350" align="left">
                                <ul class="list list-order">
                                    @foreach($lastestOrder as $order)
                                        @php $simDat = str_replace('.', '', $order->sosim) @endphp
                                        <li>
                                            <p class="order-item">
                                                <span class="order-item-name">{{$order->hoten}}</span>
                                                <span class="status">mới đặt</span>
                                                <span class="order-item-phone">Đặt sim {{substr($simDat, 0, 4)}}....{{substr($simDat, -2)}}</span>
                                            </p>
                                        </li>
                                    @endforeach
                                </ul>
                            </marquee>
                        </div>
                    </div>
                    @endif
                    @if ($lastestNews->count())
                    <div class="panel panel-arrows panel-orange panel-news">
                        <div class="panel-header">
                            <span>TIN HOT</span>
                        </div>
                        <div class="panel-content">
                            <ul class="list list-news">
                                @foreach($lastestNews as $news)
                                    <li>
                                        <a rel="nofollow" href="{{route('frontend.news.detail', ['slug' => $news->slug, 'id' => $news->id])}}">
                                            <span>{{$news->title}}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
					@else
						<div class="panel panel-arrows panel-orange panel-news hide"></div>
                    @endif
                </aside>
            </div>
        </div>
    </section>
	<?php if (isMobile()) : ?>
    @if (!empty($web_foot))
        <section class="seo-box-hotline">
			 @if (!empty($web_foot))
				<div class="onheadfoot">{!! $web_foot !!}</div>
			@endif
		</section>
    @endif
    <?php endif; ?>
    <!-- Footer -->
    <div class="clearfix"></div>
    <footer>
        <div class="container">
            <div class="row row-f-30">
                <div class="col-md-4">
                    <div class="logo-footer">
					<img src="{!! !empty(config('domainInfo')['logo']) ? config('domainInfo')['logo'] : url('frontend/icon/logo.png') !!}" class="img-responsive" alt="{{config('domainInfo')['domain_name']}}" />
					</div>
					<br>
                    {!! !empty(config('domainInfo')['footer_box_1']) ? config('domainInfo')['footer_box_1'] : '' !!}
                    <p class="rcopyright">Copyright {{date('Y')}} &copy; {{config('domainInfo')['domain_name']}}</p>
                </div>
                <div class="col-md-8">
                    <div class="list-company row clearfix">
                        {!! !empty(config('domainInfo')['footer_box_2']) ? config('domainInfo')['footer_box_2'] : '' !!}
                        {!! !empty(config('domainInfo')['footer_box_3']) ? config('domainInfo')['footer_box_3'] : '' !!}
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- end Footer -->
	<!-- START Check hidden hotline -->
	<?php
		$hiddenHotline = false;
		$currentTime = date('H');
		$openTime = !empty(config('domainInfo')['hotline_open']) ? (float)config('domainInfo')['hotline_open'] : 0;
		$closeTime = !empty(config('domainInfo')['hotline_close']) ? (float)config('domainInfo')['hotline_close'] : 0;

		if ($openTime != 0 && $closeTime != 0 && ($currentTime <= $openTime || $currentTime >= $closeTime)) { // open & close
			$hiddenHotline = true;
		} elseif ($openTime != 0 && $closeTime == 0 && $currentTime < $openTime) { // open
			$hiddenHotline = true;
		} elseif ($openTime == 0 && $closeTime != 0 && $currentTime > $closeTime) { // close
			$hiddenHotline = true;
		}
	?>
	@if(!$hiddenHotline)
	<script>
        @if(!empty(config('domainInfo')['hotlineList']))
        var hotline = '{!! json_encode(config('domainInfo')['hotlineList']) !!}';
        var hotline2 = $.parseJSON(hotline);
        shuffle(hotline2);
        renderHotline(hotline2);
        @endif
    </script>
	{!! config('domainInfo')['chat_script'] !!}
	@else
	<script>
	$('.hotlinebottom').css('display','none');
	$('.form-groups.group-submit .txt-help').css('display','none');
	</script>
	@endif
	<!-- END Check hidden hotline -->
    @if (!empty(config('domainInfo')['ads_left']) && !empty(config('domainInfo')['ads_right']))
        @php
            $adsLeftUrl = isset(config('domainInfo')['ads_left_url']) ? config('domainInfo')['ads_left_url'] : '';
            $adsLeftImage = config('domainInfo')['ads_left'];
            $adsRightUrl = isset(config('domainInfo')['ads_right_url']) ? config('domainInfo')['ads_right_url'] : '';
            $adsRightImage = config('domainInfo')['ads_right'];
        @endphp
        <script type="text/javascript">
            $(document).ready(function () {
                if (!isMobileDevice()) {
                    var strAds = '<div id="banner_l" class="banner" style="top:100px"><a target="_blank" href="{{$adsLeftUrl}}"><img class="img-responsive" src="{{$adsLeftImage}}" /></a></div><div id="banner_r" class="banner" style="top:100px"><a target="_blank" href="{{$adsRightUrl}}"><img class="img-responsive"  src="{{$adsRightImage}}" /></a></div>';
                    $('body').append(strAds);
                    var $banner = $('body').find('.banner'), $window = $(window);
                    var $topDefault = 100;
                    var $top = $(this).scrollTop();
                    bodyheight = $(document).height();
                    if ($top + $topDefault < bodyheight - 600) {
                        $banner.stop().animate( { top: ( $top + $topDefault) }, 200);
                    }
                    $window.on('scroll', function(){
                        var $top = $(this).scrollTop();
                        if ($top + $topDefault < bodyheight - 600) {
                            $banner.stop().animate( { top: ( $top + $topDefault) }, 200);
                        }
                    });
                }
            });
        </script>
    @endif
    {!! !empty(config('domainInfo')['footer_code']) ? config('domainInfo')['footer_code'] : '' !!}
</body>
</html>
