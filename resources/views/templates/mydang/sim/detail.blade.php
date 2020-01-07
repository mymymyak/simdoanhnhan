@extends($templateName.'.master')

@section('title', 'Sim')

@section('content')
    <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "Product",
      "name": "Sim {{$sim['SOSIMMOIFULL']}}",
      "image": "/{{$sim['SIMURL']}}.jpg",
      "description": "Bán sim {{$sim['SOSIMMOIFULL']}} giá rẻ.",
      "mpn": "{{$sim['SIM']}}",
      "sku": "{{$sim['SIM']}}",
      "brand": {
        "@type": "Thing",
        "name": "{{$sim['MANG']}}"
      },
      "offers": {
        "@type": "Offer",
        "url ": "{{url()->current()}}",
        "priceCurrency": "VND",
        "price": "{{$sim['GIABAN2']}}",
        "availability": "https://schema.org/InStock",
        "itemCondition": "https://schema.org/NewCondition",
        "category": "{{$sim['LOAI']}}",
        "seller": {
          "@type": "Organization",
          "name": "{{config('domainInfo')['domain']}}"
        }
      }
    }
</script>
<div class="col-12 border p-3 mt-2 " style="border-radius: .5rem;">
            <div class="row">
                <div class="col-md-6">
    

        <p class="mb-2 d-inline-block">Số Sim:</p>
        <h2 class="mb-2 font-weight-bold text-danger fs-130 d-inline-block ml-2">{{$sim['SOSIMMOIFULL']}}</h2><br/>
    
        <p class="mb-2 d-inline-block">Giá bán:</p>
        <span class="font-weight-bold fs-120">{{$sim['GIABAN']}}</span><br/>
  
        <p class="mb-2 d-inline-block">Mạng di động:</p>
        <span>
            <img src="/frontend/images/{{$sim['MANGIMG']}}.gif" alt="{{$sim['MANG']}}" />
        </span><br/>
   
       <p class="mb-2 d-inline-block">Loại sim:</p>
        <span><a TARGET="_blank" href="/{{$sim['LOAIURL']}}"><strong>{{$sim['LOAI']}}</strong></a></span>
    
</div>
  <div id="simImgSlider" class="carousel slide col-md-6 d-flex align-items-center mt-3 mt-md-0" data-ride="carousel">
    <div class="carousel-inner">
                   
        <img src="/{{$sim['SIMURL']}}.jpg" data-qazy="true" class="mx-auto d-block align-middle img-fluid"/>
    </div>
</div>
</div>
</div>

<?php if (!empty($suggest)) : ?>
<h2 style="font-size: 1.17em;font-weight: 400;margin-left: 10px;margin-top: 10px;">SIM SỐ ĐẸP GẦN GIỐNG</h2>
<div class="sugges-list ">
    <div class="divTable">
        <div class="divTableBody tableAjax">
            <div class="divTableRow hidden-xs">
                <div class="divTableCell">Số sim</div>
                <div class="divTableCell">Giá bán</div>
                <div class="divTableCell hidden-xs">Mạng</div>
                <div class="divTableCell hidden-xs">Loại</div>
                <div class="divTableCell ms-btn">Mua sim</div>
            </div>
            <?php
            foreach ($suggest as $item) {
                $line = (object) $item;
                $sosim = $line->sim;
				if ($sim['SIMURL'] == $sosim) {continue;}
                $mang = checkmang($sosim);
                $mangimg = khongdau($mang);
                $loai = getLoaiSimByCatId($line->cat_id);
                if ($line->price <= 0)
                    $gia = "(Đã bán)";
                elseif ($line->price == 1)
                    $gia = "Vui lòng gọi";
                else {
                    $giaban = $line->price;
                    $gia = number_format($giaban, 0, '', '.');
                }
                $sim_url = $sosim;
                ?>
                <div class="divTableRow">
                    <div class="divTableCell cell-left">
                        <a class="green bold sosim" href="/<?= $sim_url ?>"><?= $line->simfull ?></a>
                    </div>
                    <div class="divTableCell cell-right cell-price"><b><?= $gia ?>₫</b></div>
                    <div class="divTableCell hidden-xs">
                        <div class="mang">
                            <img src="<?= '/frontend/images/' . $mangimg . '.gif' ?>" alt="<?= $mang ?>" />
                        </div>
                    </div>
                    <div class="divTableCell hidden-xs"><?= $loai ?></div>
                    <div class="divTableCell"><a class="font-size-12 order-btn" rel="nofollow" href="/<?= $sim_url ?>">Mua ngay</a></div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php endif; ?>
<?php if (!$sim['DABAN']) : ?>
<div class="form-order border" id="form-order"  style="border-radius: .5rem;">
   
    <form name="order_form" id="order_form" style="padding-top:30px;padding-bottom:30px">
        <h2 class="text-uppercase fs-150 font-weight-bold text-center">ĐẶT MUA SIM </h2>
        <input type="hidden" name="order_sim" value="{{$sim['SOSIM']}}">
        <input type="hidden" name="order_price" value="{{$sim['GIABAN']}}">
        <input type="hidden" name="order_telco" value="{{$sim['MANG']}}">
        <div class="form-groups">
            <label>Họ tên</label>
            <div class="form-input">
                <input type="text" name="order_name" id="hoten" placeholder="Nhập họ tên của bạn" />
            </div>
        </div>
        <div class="form-groups">
            <label>Điện thoại liên hệ <span class="red">*</span></label>
            <div class="form-input">
                <input type="text" name="order_phone" id="order_phone" placeholder="Nhập số điện của bạn" />
            </div>
        </div>
		<div class="form-groups">
            <label>Mã khuyến mại (nếu có)</label>
            <div class="form-input">
                <input type="text" name="order_address" id="diachi" placeholder="Mã khuyến mại" />
            </div>
        </div>
        <?php $clshot = isMobile() ? ' class="dt-hot hotdetail"' : ' class="dt-hot"'; ?>
		<?php
		$vip = '';
		if ($sim['GIABAN2'] >= 10000000) {
			$vip = 'vip';
		} elseif($sim['GIABAN2'] >= 3000000 && $sim['GIABAN2'] < 10000000) {
			$vip = 'btn_310';
		} elseif($sim['GIABAN2'] > 0 && $sim['GIABAN2'] < 3000000) {
			$vip = 'btn_030';
		}
		?>
        <script>var classVip = '<?php echo $vip; ?>'</script>
	    <?php  $hotline = getHotLine(config('domainInfo')['hotlineList']);?>
        <div class="form-group text-center mb-0">
			<label>&nbsp;</label>
            <input type="button" onclick="sendOrder()" value="Đặt sim" class="btn-dat-sim btn btn-orange text-uppercase font-weight-bold px-5"  /><br/>
             <p class="txt-help">Đặt sim trực tiếp qua Hotline: <a<?= $clshot ?> href="tel:{{$hotline['hot']}}">{{$hotline['hot']}}</a></p>
			
        </div>
    </form>
</div>
 
<div class="hd border"  style="border-radius: .5rem;">
   
<h3>Cách thức mua sim <?= $sim['SOSIMMOIFULL'] ?>:</h3>
<ul><li>Đặt mua sim trên website hoặc điện Hotline</li><li>NVKD sẽ gọi lại tư vấn và xác nhận đơn hàng</li><li>Nhận sim tại nhà, kiểm tra thông tin chính chủ và thanh toán cho người giao sim </li></ul>
<h3 class="h32">Cách thức giao sim <?= $sim['SOSIMMOIFULL'] ?>:</h3>
<ul><li>Cách 1: <strong>{{strtoupper(config('domainInfo')['domain_name'])}}</strong> sẽ giao sim trong ngày và thu tiền tại nhà <i>(áp dụng tại các thành phố, thị trấn lớn)</i></li><li>Cách 2: Quý khách đến cửa hàng <strong>{{strtoupper(config('domainInfo')['domain_name'])}}</strong> để nhận sim trực tiếp <i>(Danh sách của hàng ở chân website)</i></li><li>Cách 3: <strong>{{strtoupper(config('domainInfo')['domain_name'])}}</strong> sẽ giao sim theo đường bưu điện và thu tiền tại nhà. </li></ul>

<p><strong>CHÚ Ý</strong>: <i>Quý khách sẽ không phải thanh toán thêm bất kỳ 1 khoản nào khác ngoài giá sim</i></p>

<p><em>Chúc quý khách gặp nhiều may mắn khi sở hữu thuê bao <?= $sim['SOSIMMOIFULL'] ?></em></p>
</div>
<?php endif; ?>
<script>var class2tr = '<?php echo isMobile() ? 'hotline_mobile' : 'hotlime_desktop'; ?>'</script>
@stop
