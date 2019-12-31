<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="vi" xml:lang="vi">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@if(!empty($web_title)){{$web_title}}@else @yield('title')@endif</title>
    @if(!empty($web_description))<meta name="description" content="{{$web_description}}">@endif
    <meta name="theme-color" content="#259D99"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Mobile Specific Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="{!! url('frontend/css/main.css') !!}">
    <link rel="stylesheet" href="{!! url('frontend/css/responsive.css') !!}">
    <link href="{!! !empty(config('domainInfo')['logo']) ? config('domainInfo')['logo'] : url('frontend/icon/favicon.png') !!}" type="image/x-icon" rel="shortcut icon">
    <link href="https://fonts.googleapis.com/css?family=Muli:400,600&display=swap&subset=vietnamese" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.public/js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="{!! url('frontend/js/jquery.min.js') !!}"></script>
    <script src="{!! url('frontend/js/popper.min.js') !!}"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="{!! url('frontend/js/main.js') !!}"></script>
    <base href="{{$baseUrl}}/" />
    {!! !empty(config('domainInfo')['header_code']) ? config('domainInfo')['header_code'] : '' !!}
</head>
<body>
    <header>
        <div class="container">
            <div class="row">
                <a href="/"><span class="header-logo"><i class="logo-header"></i></span></a>
                <span class="btn-open-menu"><i class="icon-open-menu"></i></span>
                <div class="search-groups">
                    <h1><a href="/"><img src="{!! !empty(config('domainInfo')['logo']) ? config('domainInfo')['logo'] : url('frontend/icon/logo.png') !!}" style="height: 30px"/></a></h1>
                    <form id="timkiem" name="timkiem" method="post" onsubmit="return simple_search(this.search.value,'/sim-so-dep-');">
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
                            <input type="image" id="imagesubmit" alt="Login" src="{!! url('frontend/images/search_icon.png') !!}" />
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
                        <li><a href="#" class="hd-icon icon amduong">Bói sim</a></li>
                        <li><a href="#" class="hd-icon icon amduong2">Tìm sim phong thủy</a></li>
                        <li><a href="{{route('frontend.news.list')}}" class="hd-icon icon iinfo">Kiến thức sim</a></li>
                        <li><a href="{{route('frontend.page.detail',['slug' => 'cach-mua-sim-va-thanh-toan'])}}" class="hd-icon icon debit_card">Thanh toán</a></li>
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
                    <div class="hotlinebottom" id="hlbt"><a class="hotline" href="tel:098.858.6699"> <div id="phone"><span>&nbsp;</span></div><div></div>098.858.6699</a></div>
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
                    <div class="panel panel-arrows panel-orange">
                        <div class="panel-header">
                            <span>THÔNG TIN CẦN BIẾT</span>
                        </div>
                        <div class="panel-content">
                            <div class="block list-group">
                                
                            </div>
                        </div>
                    </div>
                    @if (!empty($lastestOrder))
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
                    @if (!empty($lastestNews))
                    <div class="panel panel-arrows panel-orange panel-news">
                        <div class="panel-header">
                            <span>TIN HOT</span>
                        </div>
                        <div class="panel-content">
                            <ul class="list list-news">
                                @foreach($lastestNews as $news)
                                    <li>
                                        <a rel="nofollow" href="{{route('frontend.news.detail', ['slug' => $news->slug])}}">
                                            <span>{{$news->title}}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif
                </aside>
            </div>
        </div>
    </section>
    <!-- Footer -->
    <div class="clearfix"></div>
    <footer>
        <div class="container">
            <div class="row row-f-30">
                <div class="col-md-4">
                    <div id="hotline-ft" class="hotline-ft text-right">
                        <p class="title">Hotline tư vấn</p>
                        <a href="tel:09.6888.7888">09.6888.7888</a>
                    </div>
                    <div class="company">
                        <h1 class="title">CHỌN SIM VIETTEL - CHONSIMVIETTEL.com</h1>
                        <div class="company-description">
                            <p>CHỌN SIM VIETTEL là thương hiệu bán sim số đẹp với trụ sở chính tại Hưng Yên và cơ sở trên toàn quốc. Website chính thức là CHONSIMVIETTEL.com Phone: 09.6888.7888</p>							    </div>
                    </div>
                    <div class="social">
                        <a href="#" target="_blank" class="facebook" rel="nofollow"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                        <a href="#" target="_blank" class="twitter" rel="nofollow"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                        <a href="#" target="_blank" class="youtube" rel="nofollow"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                        <a href="#" target="_blank" class="youtube" rel="nofollow"><i class="fa fa-pinterest" aria-hidden="true"></i></a>
                    </div>
                    <a target="_blank" href="https://www.dmca.com/Protection/Status.aspx?ID=43180d81-3919-44c6-ba8e-8b0b84fc5ef4" title="DMCA.com Protection Status" class="dmca-badge"> <img src ="https://images.dmca.com/Badges/dmca-badge-w150-5x1-04.png?ID=43180d81-3919-44c6-ba8e-8b0b84fc5ef4"  alt="DMCA.com Protection Status" /></a>  <script src="https://images.dmca.com/Badges/DMCABadgeHelper.min.js"> </script>
                    <p class="rcopyright">Copyright 2018 &copy; CHỌN SIM VIETTEL</p>
                </div>
                <div class="col-md-8">
                    <div class="list-company row clearfix">
                        <div class="item col-xs-12 col-md-6">
                            <p class="title">Hà Nội</p>
                            <div class="info">
                                <p><i class="fa fa-map-marker" aria-hidden="true"></i> 55B Ô Chợ Dừa, Q.Đống Đa.</p>
                                <p><i class="fa fa-map-marker" aria-hidden="true"></i> 84 Nguyễn Chí Thanh, Q.Đống Đa.</p>
                                <p><i class="fa fa-map-marker" aria-hidden="true"></i> 222 Nguyễn Văn Cừ, Q.Long Biên.</p>
                            </div>
                        </div>
                        <div class="item col-xs-12 col-md-6">
                            <p class="title">Hồ Chí Minh: </p>
                            <div class="info">
                                <p><i class="fa fa-map-marker" aria-hidden="true"></i> 538 Nguyễn Thị Minh Khai, P2, Quận 3.</p>
                                <p><i class="fa fa-envelope" aria-hidden="true"></i> Số 49B Trần Hưng Đạo , Phường 6 , Quận 5.</p>
                                <p><i class="fa fa-phone" aria-hidden="true"></i> Số 84 Trương Vĩnh Ký , Phường Tân Thành , Quận Tân Phú.</p>
                            </div>
                        </div>
                        <div class="item col-xs-12 col-md-6">
                            <p class="title">Đà Nẵng</p>
                            <div class="info">
                                <p><i class="fa fa-map-marker" aria-hidden="true"></i> 301 Nguyễn Văn Linh, Q.Thanh Khê.</p>
                            </div>
                        </div>
                        <div class="item col-xs-12 col-md-6">
                            <p class="title">Bắc Ninh</p>
                            <div class="info">
                                <p><i class="fa fa-map-marker" aria-hidden="true"></i> 69 Trần Hưng Đạo, P.Tiền An.</p>
                            </div>
                        </div>
                        <div class="item col-xs-12 col-md-6">
                            <p class="title">Hải Dương</p>
                            <div class="info">
                                <p><i class="fa fa-map-marker" aria-hidden="true"></i> 32 Trần Hưng Đạo, TP.Hải Dương.</p>
                            </div>
                        </div>
                        <div class="item col-xs-12 col-md-6">
                            <p class="title">Tuyên Quang</p>
                            <div class="info">
                                <p><i class="fa fa-map-marker" aria-hidden="true"></i> 257 Quang Trung, P.Phan Thiết, TP.Tuyên Quang.</p>
                            </div>
                        </div>
                        <div class="item col-xs-12 col-md-6">
                            <p class="title">Hưng Yên</p>
                            <div class="info">
                                <p><i class="fa fa-map-marker" aria-hidden="true"></i> Số 76 - 78 Tòa nhà BIDV, vòng xuyến Văn Giang, TT Văn Giang</p>
                            </div>
                        </div>
                        <div class="item col-xs-12 col-md-6">
                            <p class="title">Lạng Sơn</p>
                            <div class="info">
                                <p><i class="fa fa-map-marker" aria-hidden="true"></i> 54 Lê Lợi, Vĩnh Trại, TP.Lạng Sơn.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <div class="suggest">
        <div class="container">
            <div class="row">
                <div class="footer-menu">
                    <ul>

                        <li><a href="#" rel="nofollow" target="_blank">Giới thiệu</a></li>
                        <li><a href="/bai-viet/cach-mua-sim-va-thanh-toan" rel="nofollow" target="_blank">Mua hàng và Thanh toán</a></li>
                        <li><a href="#" rel="nofollow" target="_blank">Điều khoản &amp; điều kiện</a></li>
                        <li><a href="#" rel="nofollow" target="_blank">Chính sách đổi trả sim</a></li>
                        <li><a href="#" rel="nofollow" target="_blank">Chính sách bảo mật</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- end Footer -->
    <script>
        var hotline = [
            [159, "09.6888.7888", "09.6888.7888", "Hương Giang", 'huonggiang.png'],
        ];
        shuffle(hotline);
        renderHotline(hotline);
    </script>
    {!! !empty(config('domainInfo')['footer_code']) ? config('domainInfo')['footer_code'] : '' !!}
</body>
</html>