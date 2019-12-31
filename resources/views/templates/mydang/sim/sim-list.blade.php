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
<div class="row">
    <div class="table-responsive mt-1">
                <table class="table-custom-1 table table-bordered table-striped border-0 font-weight-500">
            <thead>
                <tr>
             <th class="text-center border-white" scope="col">STT</th>
            <th class="text-center border-white" scope="col">Sim số đẹp</th>
            <th class="text-center border-white" scope="col">Giá bán</th>
            <th class="text-center border-white" scope="col">Mạng</th>
            <th class="text-center border-white px-0" scope="col">Loại</th>
            <th class="text-center border-white" scope="col">Đặt mua</th>
           
        </tr>
    </thead>
            <tbody>
    @if(!empty($listSim))
        @foreach ($listSim as $key => $sim)
			<?php
			$sim = (object) $sim;
			$sim_view = $str_nhomso != '/' ? preg_replace($str_nhomso . '/', $str_hl, $sim->simfull) : $sim->simfull;
			?>
           <tr>
               <th class="text-center align-middle  d-md-table-cell" scope="row"><span class="stt">{{ $offsets + $key + 1 }}</span></th>
                <td class="text-center text-nowrap text-center align-middle">
                    <a class="text-danger font-weight-bold fs-120" href="/{{$sim->sim}}">{!! $sim_view !!}</a>
                </td>
                <td class="text-center align-middle">{{ number_format($sim->price) }}₫</td>
                <td class="text-center align-middle">
                    <div class="mang">
                        @if(isset($sim->telco) && isset($telco[$sim->telco]) && $telco[$sim->telco]['tenmang'])
                            <img src="<?= '/frontend/images2/' . $telco[$sim->telco]['tenmang'] . '.png' ?>" alt="{{$telco[$sim->telco]['tenmang']}}"/>
                        @endif
                    </div>
                </td>
                <td class="text-center align-middle">{{$cat[$sim->cat_id]}}</td>
                <td class="text-nowrap text-center align-middle">
                    <a class="btn btn-warning btn-sm" href="/{{$sim->sim}}" rel="nofollow">Mua ngay</a>
            </td>
        </tr>
        @endforeach
    @endif
</tbody>
</table>
</div>
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
