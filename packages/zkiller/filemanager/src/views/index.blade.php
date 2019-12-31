<!DOCTYPE html>
<html>
    <head>
        <title>Media</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="{!! url('bootstrap/css/bootstrap.min.css') !!}">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="{!! url('zkiller_upload/css/zkiller_upload.css') !!}" />
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation">
                            <a href="#upload" aria-controls="upload" role="tab" data-toggle="tab">Upload</a>
                        </li>
                        <li role="presentation" class="active">
                            <a href="#media-list" aria-controls="media-list" role="tab" data-toggle="tab">Media</a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane" id="upload">Upload</div>
                        <div role="tabpanel" class="tab-pane active" id="media-list">
                            <div class="row">
                                <div class="col-lg-3">
                                    <select id="media-attachment-filters" class="form-control">
                                        <option value="all">All media items</option>
                                        <option value="image">Images</option>
                                        <option value="audio">Audio</option>
                                        <option value="video">Video</option>
                                        <option value="unattached">Unattached</option>
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <select id="media-attachment-date-filters" class="form-control">
                                        <option value="all">All dates</option>
                                        <option value="0">August 2016</option>
                                        <option value="1">May 2016</option>
                                        <option value="2">February 2016</option>
                                        <option value="3">January 2016</option>
                                        <option value="4">December 2015</option>
                                        <option value="5">September 2013</option>
                                        <option value="6">July 2013</option>
                                        <option value="7">April 2013</option>
                                        <option value="8">March 2013</option>
                                        <option value="9">July 2012</option>
                                        <option value="10">June 2012</option>
                                        <option value="11">July 2011</option>
                                        <option value="12">January 2011</option>
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <div class="pull-right">
                                        <input class="form-control" placeholder="Search" name="media_search" />
                                    </div>
                                </div>
                                <div class="content-list col-lg-12">
                                    @foreach($listFile as $file) 
                                        {!! $file !!}
                                    @endforeach
                                </div>
                                <div class="col-lg-12">
                                {!! $listFile->render() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script src="{!! url('plugins/jQuery/jquery-2.2.3.min.js') !!}"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="{!! url('bootstrap/js/bootstrap.min.js') !!}" type="text/javascript"></script>
    <script src="{!! url('zkiller_upload/js/zkiller_upload.js') !!}"></script>
</html>