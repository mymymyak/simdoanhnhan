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
             <th class="text-center border-white d-none d-md-table-cell" scope="col">STT</th>
            <th class="text-center border-white" scope="col">Sim số</th>
            <th class="text-center border-white" scope="col">Giá bán</th>
            <th class="text-center border-white d-none d-md-table-cell" scope="col">Mạng</th>
            <th class="text-center border-white px-0 d-none d-md-table-cell" scope="col">Loại</th>
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
               <th class="text-center align-middle d-none d-md-table-cell" scope="row"><span class="stt">{{ $offsets + $key + 1 }}</span></th>
                <td class="text-center text-nowrap text-center align-middle">
                    <a class="text-danger font-weight-bold fs-120" href="/{{$sim->sim}}">{!! $sim_view !!}</a>
                </td>
                <td class="text-center align-middle">{{ number_format($sim->price) }}₫</td>
                <td class="text-center align-middle d-none d-md-table-cell">
                    <div class="mang">
                        @if(isset($sim->telco) && isset($telco[$sim->telco]) && $telco[$sim->telco]['tenmang'])
                            <img src="<?= '/frontend/images2/' . $telco[$sim->telco]['tenmang'] . '.png' ?>" alt="{{$telco[$sim->telco]['tenmang']}}"/>
                        @endif
                    </div>
                </td>
                <td class="text-center align-middle d-none d-md-table-cell">{{$cat[$sim->cat_id]}}</td>
                <td class="text-nowrap text-center align-middle">
                    <a class="btn btn-warning btn-sm" href="/{{$sim->sim}}" rel="nofollow">Mua ngay</a>
            </td>
        </tr>
        @endforeach
    @else
    <div class="row">
    <div class="col-12 border rounded px-1 py-3 mt-3 bg-light text-center">
        <p class="align-middle mb-0">Sim chưa được cập nhật lên web</p>
        <p class="align-middle mb-0">Quý khách có nhu cầu sử dụng số dạng này, hãy liên hệ với chúng tôi để được hỗ trợ nhanh nhất!</p>
        <p class="mt-2 mb-0">
            <a class="btn-support-chat btn btn-primary rounded-pill text-left font-weight-bold pl-1 fs-15 mr-3 pr-5" href="https://zalo.me/" title="Chat Zalo"><img src="/frontend/icon/zalo-icon.svg" alt="Chat Zalo" height="24" class="mr-1">Chat Zalo</a>
            <a class="btn-support-chat btn btn-primary rounded-pill text-left font-weight-bold pl-1 fs-15 mr-3" href="https://m.me/" title="Chat Facebook"><img src="/frontend/icon/message_fb.png" alt="Chat Zalo" height="24" class="mr-1">Chat Facebook</a>
            <a class="badge badge-pill badge-warning text-left font-weight-bold pl-1 fs-15 mt-2" href="tel:0888106699" title=" 0888.10.6699"><img src="/frontend/icon/call-icon.svg" alt=" 0888.10.6699" height="24" class="mr-1"> 0888.10.6699</a>
        </p>
    </div>
</div>
    @endif
</tbody>
</table>
</div>
</div>
<div class="simsodep">
    <form style="margin-bottom: 0px;" id="filter_form" name="filter_form" method="get" action="">
        <div class="box-list-sim-top">
            <div class="row" style="display: none;">
                <div class="col-md-3">
                    <select name="m10so_filter" id="m10so_filter" onchange="submit_filter();" class="form-control">
                        <option value="0">Tất cả</option>
                        <option value="09">Đầu số 09</option>
                        <option value="08">Đầu số 08</option>
                        <option value="07">Đầu số 07</option>
                        <option value="05">Đầu số 05</option>
                        <option value="03">Đầu số 03</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="price_filter" id="price_filter" onchange="submit_filter();" class="form-control">
                        <option value="0">Mức giá</option>
                        <option value="0,1">Dưới 1 triệu</option>
                        <option value="1,3">1 - 3 triệu</option>
                        <option value="3,5">3 - 5 triệu</option>
                        <option value="5,10">5 - 10 triệu</option>
                        <option value="10,20">10 - 20 triệu</option>
                        <option value="20,50">20 - 50 triệu</option>
                        <option value="50,100">50 - 100 triệu</option>
                        <option value="100,0">Trên 100 triệu</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="telco_filter" id="telco_filter" onchange="submit_filter();" class="form-control">
                        <option value="">Mạng</option>
                        <option value="viettel">Viettel</option>
                        <option value="vinaphone">Vinaphone</option>
                        <option value="mobifone">Mobifone</option>
                        <option value="vietnamobile">Vietnamobile</option>
                        <option value="gmobile">Gmobile</option>
                        <option value="itelecom">iTelecom</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="giaban_filter" id="giaban_filter" onchange="submit_filter();" class="form-control">
                        <option value="0">Sắp xếp</option>
                        <option value="1">Giá thấp đến cao</option>
                        <option value="2">Giá cao đến thấp</option>
                        <!-- <option value="3">Số đẹp giá tốt</option> -->
                        <!-- <option value="4">Số mới cập nhật</option> -->
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="sim_type_filter" id="sim_type_filter" onchange="submit_filter();" class="form-control">
                        <option value="">Tất cả</option>
                                                <option value="55">Sim tứ quý</option>
                                                <option value="56">Sim ngũ quý</option>
                                                <option value="57">Sim lục quý</option>
                                                <option value="58">Sim tam hoa</option>
                                                <option value="59">Sim tam hoa kép</option>
                                                <option value="64">Sim taxi</option>
                                                <option value="65">Sim lặp kép</option>
                                                <option value="66">Sim gánh đảo</option>
                                                <option value="67">Sim đặc biệt</option>
                                                <option value="68">Sim năm sinh</option>
                                                <option value="69">Sim đầu số cổ</option>
                                                <option value="70">Số máy bàn</option>
                                                <option value="71">Sim tứ quý giữa</option>
                                                <option value="72">Sim ngũ quý giữa</option>
                                                <option value="73">Sim lục quý giữa</option>
                                                <option value="74">Sim tự chọn</option>
                                                <option value="63">Sim tiến lên</option>
                                                <option value="62">Sim ông địa</option>
                                                <option value="61">Sim thần tài</option>
                                                <option value="60">Sim lộc phát</option>
                                            </select>
                </div>
            </div>
        </div>
    </form>
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
