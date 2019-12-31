@extends('master')

@section('title', 'Sim')

@section('content')
    @if (!empty($web_h1) || !empty($web_head))
        <div class="seo-box">
            <h1>{{$web_h1}}</h1>
            <div class="onheadfoot">{!! $web_head !!}</div>
        </div>
    @endif
<div class="ajax-content">
    @include('sim/sim-list')
</div>
    <?php if (!isMobile()) : ?>
    @if (!empty($web_foot))
        <div class="seo-box">
            <div class="onheadfoot">{!! $web_foot !!}</div>
        </div>
    @endif
    <?php endif; ?>
@stop