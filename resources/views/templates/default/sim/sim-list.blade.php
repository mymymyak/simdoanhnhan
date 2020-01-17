@php
    $telco = config('global.LOAIMANG');
    $cat = config('global.LOAISIM');
@endphp
@include($templateName.'.sim.filter')
<?php
$str_nhomso = "/";
$str_hl = "";
$str_hl2 = "";
if (!empty($tag)) {
	$hl_tag = preg_replace("/\*+/", ",*,", $tag);
	$hl_tag = preg_replace("/(x+)/", ",$1,", $hl_tag);
	//$hl_tag = preg_replace('/[^\,+^/', ',',$hl_tag);
	$part = explode(',', $hl_tag);
	$k    = 1;
	foreach ($part as $key => $value) {
		if ($value == "*") {
			$str_nhomso = $str_nhomso . "(.*)";
			$str_hl     = $str_hl . "$" . $k;
			$str_hl2    = $str_hl2 . "$" . $k;
		} elseif ($value == "x") {
			$str_nhomso = $str_nhomso . "(.{1})";
			$str_hl     = $str_hl . "$" . $k;
			$str_hl2    = $str_hl2 . "$" . $k;
		} elseif ($value == "") {
			$k --;
		} elseif (strpos('xx', $value) !== false) {
			$str_nhomso = $str_nhomso . "(.{" . strlen($value) . "})";
			$str_hl     = $str_hl . "$" . $k;
			$str_hl2    = $str_hl2 . "$" . $k;
		} else {
			$str_nhomso = $str_nhomso . "(" . $value . ")";
			$str_hl     = $str_hl . "<font color='red'>$" . $k . "</font>";
			$str_hl2    = $str_hl2 . "<strong>$" . $k . "</strong>";
		}
		$k ++;
	}
	$str_nhomso = $str_nhomso;
}
?>
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
    @if(!empty($listSim))
        @foreach ($listSim as $key => $sim)
			<?php
			$sim = (object) $sim;
			$sim_view = $str_nhomso != '/' ? preg_replace($str_nhomso . '/', $str_hl, $sim->simfull) : $sim->simfull;
			?>
            <div class="divTableRow">
                <div class="divTableCell hidden-xs gray"><span class="stt">{{ $offsets + $key + 1 }}</span></div>
                <div class="divTableCell cell-left">
                    <a class="green bold sosim" href="/{{$sim->sim}}">{!! $sim_view !!}</a>
                </div>
                <div class="divTableCell cell-right cell-price">{{ number_format($sim->price) }}₫</div>
                <div class="divTableCell hidden-xs">
                    <div class="mang">
                        @if(isset($sim->telco) && isset($telco[$sim->telco]) && $telco[$sim->telco]['tenmang'])
                            <img src="<?= '/frontend/images2/' . $telco[$sim->telco]['tenmang'] . '.png' ?>" alt="{{$telco[$sim->telco]['tenmang']}}"/>
                        @endif
                    </div>
                </div>
                <div class="divTableCell hidden-xs">{{$cat[$sim->cat_id]}}</div>
                <div class="divTableCell">
                    <a class="font-size-12 order-btn" href="/{{$sim->sim}}" rel="nofollow">Mua ngay</a></div>
            </div>
        @endforeach
    @endif
</div>
<div class="text-center" style="margin-bottom:10px">
    @include('pagination.default', ['paginator' => $paginatedItems, 'link_limit' => 5])
</div>
<div>
	<?php if (!empty($linkgoiy)) : ?>
    <div class="boxgoiy suggest-duoisim" style="padding-top:12px;margin-bottom:10px">
        <span style="font-weight: bold;color: red;margin-right:5px">XEM THÊM:</span><?php foreach ($linkgoiy as $goiy) {
			echo $goiy;
		} ?></div>
	<?php endif; ?>
</div>
@include($templateName.'.sim.filter')
