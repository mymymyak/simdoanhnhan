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
    <div class="sim-detail clearfix">
        <div class="sim-info-header"><h2 class="sim-header">Thông tin số sim</h2></div>
        <ul class="sim-info">
            <li>
                <label>Sim số đẹp:</label>
                <h1 class="sim-header-title"><span class="sosim2 red2">{{$sim['SOSIMMOIFULL']}}</span></h1>
            </li>
            <li>
                <label>Giá bán:</label>
                <b class="green font-size-16">{{$sim['GIABAN']}}</b>
            </li>
            <li>
                <label>Mạng di động:</label>
                <span>
                    <amp-img src="/frontend/images/{{$sim['MANGIMG']}}.gif" alt="Bán sim {{$sim['SIMURL']}}" width="98"
                             height="22">
                    </amp-img>
        </span>
            </li>
            <li>
                <label>Loại sim:</label>
                <span><a TARGET="_blank" href="/{{$sim['LOAIURL']}}"><strong>{{$sim['LOAI']}}</strong></a></span>
            </li>
        </ul>
        <div class="col-right">
            <amp-img src="/{{$sim['SIMURL']}}.jpg" data-qazy="true" width="294"
                     height="195" alt="Mua sim {{$sim['SIMURL']}} giá rẻ"></amp-img>
        </div>

    </div>
	<?php if (!empty($suggest)) : ?>
    <h2 style="font-size: 1.17em;font-weight: 400;margin-left: 10px;margin-top: 10px;">SIM SỐ ĐẸP GẦN GIỐNG</h2>
    <div class="sugges-list">
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
				if ($sim['SIMURL'] == $sosim) {
					continue;
				}
				$mang = checkmang($sosim);
				$mangimg = khongdau($mang);
				$loai = getLoaiSimByCatId($line->cat_id);
				if ($line->price <= 0) $gia = "(Đã bán)"; elseif ($line->price == 1) $gia = "Vui lòng gọi";
				else {
					$giaban = $line->price;
					$gia    = number_format($giaban, 0, '', '.');
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
                            <amp-img src="<?= '/frontend/images/' . $mangimg . '.gif' ?>" alt="<?= $mang ?>"  width="95"
                                     height="20" > </amp-img>
                        </div>
                    </div>
                    <div class="divTableCell hidden-xs"><?= $loai ?></div>
                    <div class="divTableCell">
                        <a class="font-size-12 order-btn" rel="nofollow" href="/<?= $sim_url ?>">Mua ngay</a></div>
                </div>
				<?php } ?>
            </div>
        </div>
    </div>
	<?php endif; ?>
	<?php if (!$sim['DABAN']) : ?>
    <div class="form-order" id="form-order">
        <div class="sim-info-header"><h2 class="sim-header">MUA SIM {{$sim['SIMURL']}}</h2></div>
        <form name="order_form" id="order_form"
              style="padding-top:30px;padding-bottom:30px" method="post"  action-xhr="/sim-order" target="_top">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="order_sim" value="{{$sim['SOSIM']}}">
            <input type="hidden" name="order_price" value="{{$sim['GIABAN']}}">
            <input type="hidden" name="order_telco" value="{{$sim['MANG']}}">
            <div class="form-groups">
                <label>Họ tên</label>
                <div class="form-input">
                    <input type="text" name="order_name" id="hoten" placeholder="Nhập họ tên của bạn"/>
                </div>
            </div>
            <div class="form-groups">
                <label>Điện thoại liên hệ <span class="red">*</span></label>
                <div class="form-input">
                    <input type="text" name="order_phone" id="order_phone" required placeholder="Nhập số điện của bạn"/>
                </div>
            </div>
			<?php $clshot = isMobile() ? ' class="dt-hot hotdetail"' : ' class="dt-hot"'; ?>
			<?php
			$vip = '';
			if ($sim['GIABAN2'] >= 10000000) {
				$vip = 'vip';
			} elseif ($sim['GIABAN2'] >= 3000000 && $sim['GIABAN2'] < 10000000) {
				$vip = 'btn_310';
			} elseif ($sim['GIABAN2'] > 0 && $sim['GIABAN2'] < 3000000) {
				$vip = 'btn_030';
			}
			?>
            <div class="form-groups group-submit">
                <label>&nbsp;</label>
                <input type="submit"  value="Đặt sim" class="submit-btn"/>
                <p class="txt-help">Đặt sim trực tiếp qua Hotline:
                    <a<?= $clshot ?> href="tel:09.6888.7888">09.6888.7888</a></p>
            </div>
            <div submit-success>
                <template type="amp-mustache">
                    <div class="success-order">
                        <div class="success-order-content"><strong>
                                <div class ="order-message" >Đặt Sim thành công!</div><br>
                            </strong>
                            <strong><br>
                                <strong>Chúng tôi sẽ kiểm tra đơn hàng<br>
                                    và chủ động </strong>
                                liên lạc lại với Quý khách! <br>
                                <br>
                            </strong><em>Xin chân thành cảm ơn!</em></div></div>
                </template>
            </div>
            <div submit-error>
                <template type="amp-mustache">
                    <div class="success-order">
                        <div class ="order-message" >Vui lòng nhập đúng thông tin hoặc gọi hotline!</div><br>
                    </div>
                </template>
            </div>
        </form>
    </div>
    <div class="hd">
        <h3>Cách thức mua sim <?= $sim['SOSIMMOIFULL'] ?>:</h3>
        <ul>
            <li>Đặt mua sim trên website hoặc điện Hotline</li>
            <li>NVKD sẽ gọi lại tư vấn và xác nhận đơn hàng</li>
            <li>Nhận sim tại nhà, kiểm tra thông tin chính chủ và thanh toán cho người giao sim</li>
        </ul>
        <h3 class="h32">Cách thức giao sim <?= $sim['SOSIMMOIFULL'] ?>:</h3>
        <ul>
            <li>Cách 1:
                <strong>{{strtoupper(config('domainInfo')['domain_name'])}}</strong> sẽ giao sim trong ngày và thu tiền tại nhà
                <i>(áp dụng tại các thành phố, thị trấn lớn)</i></li>
            <li>Cách 2: Quý khách đến cửa hàng
                <strong>{{strtoupper(config('domainInfo')['domain_name'])}}</strong> để nhận sim trực tiếp
                <i>(Danh sách của hàng ở chân website)</i></li>
            <li>Cách 3:
                <strong>{{strtoupper(config('domainInfo')['domain_name'])}}</strong> sẽ giao sim theo đường bưu điện và thu tiền tại nhà.
            </li>
        </ul>

        <p><strong>CHÚ Ý</strong>: <i>Quý khách sẽ không phải thanh toán thêm bất kỳ 1 khoản nào khác ngoài giá sim</i>
        </p>

        <p><em>Chúc quý khách gặp nhiều may mắn khi sở hữu thuê bao <?= $sim['SOSIMMOIFULL'] ?></em></p>
    </div>
	<?php endif; ?>
@stop
