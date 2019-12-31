<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Login Admin</title>
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
        <!-- iCheck -->
        <link rel="stylesheet" href="{!! url('plugins/iCheck/square/blue.css') !!}">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="#"><b>MANAGER</b>SHOP</a>
            </div>
            <!-- /.login-logo -->
            <div class="login-box-body">
                <p class="login-box-msg">Sign in to start your session</p>
                @if (session()->has('flash_message'))
                <div class="alert alert-success">
                    {{ session()->get('flash_message') }}
                </div>
                @endif

                @if (session()->has('error_message'))
                <div class="alert alert-danger">
                    {{ session()->get('error_message') }}
                </div>
                @endif
                {!! Form::open(['route' => 'admin.login', 'method' => 'post']) !!}
                <div class="form-group has-feedback">
                    {!! Form::text('email', null, ['placeholder' => 'Email', 'class' => 'form-control', 'required' => 'required'])!!}
                    {!! errors_for('email', $errors) !!}
                </div>
                <div class="form-group has-feedback">
                    {!! Form::password('password', ['placeholder' => 'Password','class' => 'form-control', 'required' => 'required'])!!}
                    {!! errors_for('password', $errors) !!}
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label>
                                {!! Form::checkbox('remember', 'remember') !!} Remember me
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        {!! Form::submit('Login', ['class' => 'btn btn-primary btn-block btn-flat']) !!}
                    </div>
                    <!-- /.col -->
                </div>
                {!! Form::close() !!}

                <div class="social-auth-links text-center">
                    <p>- OR -</p>
                    <a href="javascript:void(0)" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using
                        Facebook</a>
                    <a href="javascript:void(0)" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using
                        Google+</a>
                </div>
                <!-- /.social-auth-links -->

                <a href="javascript:void(0)">I forgot my password</a><br>
                <a href="javascript:void(0)" class="text-center">Register a new membership</a>

            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->

        <!-- jQuery 2.2.3 -->
        <script src="{!! url('plugins/jQuery/jquery-2.2.3.min.js') !!}"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="{!! url('bootstrap/js/bootstrap.min.js') !!}" type="text/javascript"></script>
        <!-- iCheck -->
        <script src="{!! url('plugins/iCheck/icheck.min.js') !!}" type="text/javascript"></script>
        <script>
$(function () {
$('input').iCheck({
checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
});
});
        </script>
    </body>
</html>
