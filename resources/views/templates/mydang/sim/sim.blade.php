@extends($templateName.'.master')

@section('title', 'Sim')

@section('content')
    @if (!empty($web_h1) || !empty($web_head))
        <div class="seo-box">
            <h1>{{$web_h1}}</h1>
            <div class="onheadfoot">{!! $web_head !!}</div>
        </div>
    @endif
<div class="ajax-content">
    @include($templateName.'.sim.sim-list')
</div>
    <?php if (!isMobile()) : ?>
    @if (!empty($web_foot))
        <div class="seo-box" style="margin-top:10px;">
            <div class="onheadfoot">{!! $web_foot !!}</div>
        </div>
    @endif
    <?php endif; ?>
@stop
