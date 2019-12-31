@php
$telco = config('global.LOAIMANG');
$cat = config('global.LOAISIM');
@endphp
@include('sim/filter')
<div class="divTable">
    <div class="divTableBody tableAjax">
        <div class="divTableRow hidden-xs">
            <div class="divTableCell hidden-xs">STT</div>
            <div class="divTableCell">Số sim</div>
            <div class="divTableCell">Giá bán</div>
            <div class="divTableCell hidden-xs">Mạng</div>
            <div class="divTableCell hidden-xs">Loại</div>
            <div class="divTableCell ms-btn">Mua sim</div>
        </div>
    </div>
    @foreach ($listSim as $key => $sim)
        @php $sim = (object)$sim @endphp
    <div class="divTableRow">
        <div class="divTableCell hidden-xs gray"><span class="stt">{{ $offsets + $key + 1 }}</span></div>
        <div class="divTableCell cell-left">
            <a class="green bold sosim" href="/{{$sim->sim}}">{{$sim->simfull}}</a>
        </div>
        <div class="divTableCell cell-right cell-price">{{ number_format($sim->price) }}₫</div>
        <div class="divTableCell hidden-xs">
            <div class="mang">
                <img src="<?= '/frontend/images2/'.$telco[$sim->telco]['tenmang'].'.png' ?>" alt="{{$telco[$sim->telco]['tenmang']}}" />
            </div>
        </div>
        <div class="divTableCell hidden-xs">{{$cat[$sim->cat_id]}}</div>
        <div class="divTableCell"><a class="font-size-12 order-btn" href="/{{$sim->sim}}" rel="nofollow">Mua ngay</a></div>
    </div>
    @endforeach
</div>
<div class="text-center">
    @include('pagination.default', ['paginator' => $paginatedItems, 'link_limit' => 5])
</div>
