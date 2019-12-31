@extends($templateName.'.master')

@section('content')
    @if (!empty($web_h1) || !empty($web_head))
        <div class="seo-box">
            <h1>{{$web_h1}}</h1>
            <div class="onheadfoot">
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                    <tr>
                        <td valign="middle">
                            <div class="alert alert-success alert-dismissable">
                                <div style=" padding: 10px; ">
                                    <h2 style=" font-size: 25px; color: #FF9800; ">Đường link bạn truy cập hiện không tồn tại !</h2>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>1
            </div>
        </div>
    @endif
    <div class="clearfix"></div>
    @if (!empty($widgetsContent))
        @foreach($widgetsContent as $widgetContent)
            {{ Widget::run('gridView', $widgetContent) }}
        @endforeach
    @endif
    <div class="clearfix"></div>
	<?php if (!isMobile()) : ?>
    @if (!empty($web_foot))
        <div class="seo-box">
            <div class="onheadfoot">{!! $web_foot !!}</div>
        </div>
    @endif
	<?php endif; ?>
@endsection
