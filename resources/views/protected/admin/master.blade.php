<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('title') - Admin</title>
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Mobile Specific Meta -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{!! url('bootstrap/css/bootstrap.min.css') !!}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{!! url('dist/css/AdminLTE.min.css') !!}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{!! url('dist/css/skins/_all-skins.min.css') !!}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{!! url('plugins/iCheck/flat/blue.css') !!}">
    <!-- Morris chart -->
    <link rel="stylesheet" href="{!! url('plugins/morris/morris.css') !!}">
    <!-- jvectormap -->
    <link rel="stylesheet" href="{!! url('plugins/jvectormap/jquery-jvectormap-1.2.2.css') !!}">
    <!-- Date Picker -->
    <link rel="stylesheet" href="{!! url('plugins/datepicker/datepicker3.css') !!}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{!! url('plugins/daterangepicker/daterangepicker.css') !!}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{!! url('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') !!}">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css"/>
    <link rel="stylesheet" href="{!! url('css/custom.css') !!}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <header class="main-header">
        <!-- Logo -->
        <a href="index2.html" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>A</b>LT</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Admin</b>SIM</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="http://3.bp.blogspot.com/-zkq2qOn-hvk/XTlvlidrhQI/AAAAAAAAGPM/GAt8LdWaKfstHZ7oARiktrG-6e8rx_dSgCLcBGAs/h60/troll.jpg" class="user-image" alt="User Image">
                            <span class="hidden-xs"><?php if (Sentinel::check()) {
									echo Sentinel::getUser()->username;
								} ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="{!! url('dist/img/user2-160x160.jpg') !!}" class="img-circle"
                                     alt="User Image">
                                <p>
									<?php if (Sentinel::check()) {
										echo Sentinel::getUser()->username;
									} ?>
                                    <small>{{date('d/m/Y H:i', strtotime(Sentinel::getUser()->created_at))}}</small>
                                </p>
                            </li>
                            <!-- Menu Footer-->
							<?php if(Sentinel::check()) : ?>
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="{{url()->route('profiles.show', Sentinel::getUser()->id)}}"
                                       class="btn btn-default btn-flat">Profile</a>
                                </div>
                                <div class="pull-right">
                                    <a href="{{url()->route('logout')}}" class="btn btn-default btn-flat">Sign out</a>
                                </div>
                            </li>
							<?php endif; ?>
                        </ul>
                    </li>
                    <!-- Control Sidebar Toggle Button -->
                    <li>
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="http://3.bp.blogspot.com/-zkq2qOn-hvk/XTlvlidrhQI/AAAAAAAAGPM/GAt8LdWaKfstHZ7oARiktrG-6e8rx_dSgCLcBGAs/h120/troll.jpg" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p><?php if(Sentinel::check()) : ?>{{ Sentinel::getUser()->last_name }}{{ Sentinel::getUser()->first_name }}<?php endif; ?></p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <li class="header">MAIN NAVIGATION</li>
                <li class="{{ set_active_admin('admin') }}">
                    <a href="{{route('admin_dashboard')}}">
                        <i class="fa fa-home"></i><span> Home</span>
                    </a>
                </li>
                @if(isManager())
                    <li class="{{ set_active_admin('admin/domain') }} {{ set_active_admin('admin/domain/*') }}">
                        <a href="{{route('domain.index')}}">
                            <i class="fa fa-globe"></i><span> Quản lý domain</span>
                        </a>
                    </li>
                @endif
                @if(isManager())
                    <li class="{{ set_active_admin('admin/bang-so') }} {{set_active_admin('admin/bang-so-danh-muc')}} {{ set_active_admin('admin/custom-query') }} {{ set_active_admin('admin/custom-query-configuration') }}">
                        <a href="#">
                            <i class="fa fa-folder text-aqua"></i> <span>Bảng số</span>
                            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ set_active_admin('admin/bang-so') }}">
                                <a href="{{route('managers.bangso.index')}}"><i class="fa fa-circle-o"></i> Bảng số tổng</a>
                            </li>
                            <li class="{{ set_active_admin('admin/bang-so-danh-muc') }}">
                                <a href="{{route('managers.bangso.danhmuc')}}"><i class="fa fa-circle-o"></i> Bảng số danh mục</a>
                            </li>
                            <li class="{{ set_active_admin('admin/custom-query') }}">
                                <a href="{{route('managers.site.custom.query')}}"><i class="fa fa-circle-o"></i> Custom Query</a>
                            </li>
                            <li class="{{ set_active_admin('admin/custom-query-configuration') }}">
                                <a href="{{route('managers.custom.query.configuration')}}">
                                    <i class="fa fa-circle-o"></i> Cấu hình Custom Query</a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if(isManager())
                    <li class="{{ set_active_admin('admin/promotion/*') }}">
                        <a href="{{route('promotion.index')}}">
                            <i class="fa fa-book"></i><span> Quản lí khuyến mãi</span>
                        </a>
                    </li>
                @endif
                <li class="{{ set_active_admin('admin/news') }} {{set_active_admin('admin/news/*')}}">
                    <a href="#">
                        <i class="fa fa-edit"></i> <span>Post</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ set_active_admin('admin/news') }}">
                            <a href="{{route('news.index')}}"><i class="fa fa-circle-o"></i> Danh sách</a>
                        </li>
                        <li class="{{ set_active_admin('admin/news/create') }}">
                            <a href="{{route('news.create')}}"><i class="fa fa-file-text-o"></i> Thêm mới</a>
                        </li>
                    </ul>
                </li>
                @if (isAdmin())
                    <li class="{{ set_active_admin('admin/page') }}">
                        <a href="{{route('pages.index')}}">
                            <i class="fa fa-edit"></i> <span>Pages</span>
                        </a>
                    </li>
                @endif
                <li class="treeview {{ set_active_admin('admin/seo-config') }}
                {{set_active_admin('admin/seo-config/*')}} {{set_active_admin('admin/link-goi-y')}}
                {{set_active_admin('admin/sitemap-robots')}}">
                    <a href="#">
                        <i class="fa fa-globe"></i> <span>SEO CONFIG</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ set_active_admin('admin/seo-config') }}">
                            <a href="{{route('seo-config.index')}}"><i class="fa fa-circle-o"></i> Danh sách</a>
                        </li>
                        <li class="{{ set_active_admin('admin/seo-config/create') }}">
                            <a href="{{route('seo-config.create', ['type' => 0])}}"><i class="fa fa-circle-o"></i> Danh mục</a>
                        </li>
                        <li class="{{ set_active_admin('admin/seo-config/create') }}">
                            <a href="{{route('seo-config.create', ['type' => 1])}}"><i class="fa fa-circle-o"></i> Tìm kiếm</a>
                        </li>
                        <li class="{{ set_active_admin('admin/link-goi-y') }}">
                            <a href="{{route('managers.linkgoiy.index')}}"><i class="fa fa-circle-o"></i> Link Gợi ý</a>
                        </li>
                        <li class="{{ set_active_admin('admin/sitemap-robots') }}">
                            <a href="{{route('managers.sitemap.robot.index')}}"><i class="fa fa-circle-o"></i> Sitemap và Robots</a>
                        </li>
                    </ul>
                </li>
                @if (isAdmin())
                    <li class="{{ set_active_admin('admin/clear-cache') }}">
                        <a href="/admin/clear-cache" onclick="return confirm('Bạn muốn xóa cache website?')">
                            <i class="fa fa-remove"></i> <span>Xóa CACHE</span>
                        </a>
                    </li>
                @endif
                @if (isAdmin())
                    <li class="{{ set_active_admin('admin/clear-bang-so') }}">
                        <a href="/admin/clear-bang-so" onclick="return confirm('Bạn muốn xóa tất bảng số?')">
                            <i class="fa fa-remove"></i> <span>Xóa Bảng sim</span>
                        </a>
                    </li>
                @endif
                @if(isManager())
                    <li class="treeview {{ set_active_admin('admin/profiles') }} {{set_active_admin('admin/role')}}">
                        <a href="#">
                            <i class="fa fa-lock"></i>
                            <span>Users</span>
                            <span class="pull-right-container"><span class="label label-primary pull-right">{{Sentinel::getUserRepository()->count()}}</span></span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ set_active_admin('admin/profiles') }}">
                                <a href="{{route('profiles.index')}}"><i class="fa fa-users"></i> List Users</a>
                            </li>
                            @if (isAdmin())
                                <li class="{{ set_active_admin('admin/groups') }}">
                                    <a href="{{route('role.index')}}"><i class="fa fa-lock"></i> Roles</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                <li>
                    <a href="#">
                        <i class="fa fa-envelope"></i> <span>Mailbox</span>
                        <span class="pull-right-container">
                          <small class="label pull-right bg-yellow">12</small>
                          <small class="label pull-right bg-green">16</small>
                          <small class="label pull-right bg-red">5</small>
                        </span>
                    </a>
                </li>

            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>
    <div class="content-wrapper">
        <section class="content">
            @if (isAdmin())
                <form action="{{route('admin_set_domain')}}" method="post" class="sidebar-form">
                    @csrf
                    <div class="input-group">
						<?php
						$domainRes = new \App\Repositories\Domain\DomainRepository(new \App\Models\TableDomain());
						$domainList = $domainRes->getDomainActive();
						?>
                        <select class="form-control" name="domain">
                            <option value="">Choose Domain</option>
                            @foreach ($domainList as $domain)
                                <option value="{{$domain->domain}}" @php echo isset($_COOKIE['domain_setting']) && $_COOKIE['domain_setting'] == $domain->domain ? 'selected' : ''; @endphp>
                                    {{$domain->domain}}
                                </option>
                            @endforeach
                        </select>
                        <span class="input-group-btn">
                        <button type="submit" class="btn btn-flat"><i class="fa fa-save"></i></button>
                    </span>
                    </div>
                </form>
            @endif
            @include('protected.admin.message')
            @yield('content')
        </section>
    </div>

    <script src="{!! url('plugins/jQuery/jquery-2.2.3.min.js') !!}"></script>
    <script src="{!! url('js/jscolor.js') !!}"></script>
    <script src="{!! url('tinymce/js/tinymce/tinymce.js') !!}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"
            integrity="sha256-4iQZ6BVL4qNKlQ27TExEhBN1HFPvAvAMbFavKKosSWQ=" crossorigin="anonymous"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="{!! url('bootstrap/js/bootstrap.min.js') !!}" type="text/javascript"></script>
    <!-- FastClick -->
    <script src="{!! url('plugins/fastclick/fastclick.min.js') !!}"></script>
    <!-- AdminLTE App -->
    <script src="{!! url('dist/js/app.min.js') !!}" type="text/javascript"></script>
    <!-- Sparkline -->
    <script src="{!! url('plugins/sparkline/jquery.sparkline.min.js') !!}" type="text/javascript"></script>
    <!-- jvectormap -->
    <script src="{!! url('plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') !!}" type="text/javascript"></script>
    <script src="{!! url('plugins/jvectormap/jquery-jvectormap-world-mill-en.js') !!}" type="text/javascript"></script>
    <!-- daterangepicker -->
    <script src="{!! url('plugins/daterangepicker/daterangepicker.js') !!}" type="text/javascript"></script>
    <!-- datepicker -->
    <script src="{!! url('plugins/datepicker/bootstrap-datepicker.js') !!}" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="{!! url('plugins/iCheck/icheck.min.js') !!}" type="text/javascript"></script>
    <!-- SlimScroll 1.3.0 -->
    <script src="{!! url('plugins/slimScroll/jquery.slimscroll.min.js') !!}" type="text/javascript"></script>
    <!-- ChartJS 1.0.1 -->
    <script src="{!! url('plugins/chartjs/Chart.min.js') !!}" type="text/javascript"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <!-- AdminLTE for demo purposes -->
    <script src="{!! url('dist/js/demo.js') !!}" type="text/javascript"></script>
    <script src="{!! url('js/base_admin.js') !!}?v=<?= time();?>" type="text/javascript"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.2/moment.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
    <script>
		$(document).ready(function() {
			$('input').iCheck({
				checkboxClass: 'icheckbox_flat-blue',
				radioClass   : 'iradio_flat-blue'
			});
			$('.datepicker').datetimepicker({
				//	defaultDate: moment().add(1, 'd'), //defaultDate
				format    : 'YYYY-MM-DD HH:mm',
				sideBySide: true,
				//	minDate   : moment(), //minDate
			});

			function displayPromotionDate(input) {
				if(input.val() === 'inactive') {
					$('.active-date-row').show();
					$('.expire-date-row').show();
				} else if(input.val() === 'expired') {
					$('.active-date-row').hide();
					$('.expire-date-row').hide();
				} else {
					$('.active-date-row').hide();
					$('.expire-date-row').show();
				}
			}

			displayPromotionDate($('.select-status'));
			$('.select-status').on("change", function() {
				displayPromotionDate($(this));
			});

		});
    </script>
</div>
</body>
</html>
