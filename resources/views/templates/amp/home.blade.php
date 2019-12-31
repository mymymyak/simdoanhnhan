@extends($templateName.'.master')
@section('content')
    @if (!empty($web_h1) || !empty($web_head))
        <div class="seo-box">
            <h1>{{$web_h1}}</h1>
            <div class="onheadfoot">{!! $web_head !!}</div>
        </div>
    @endif
	<?php if (!empty($listSimHot)) : ?>
    <div class="simhot">
        <h3 class="head-title">SIM HOT</h3>
        <div class="row col-mar-5">
			<?php foreach ($listSimHot as $simhot) :
			$line = (object) $simhot;
			$sosim = $line->sim;
			$mang = checkmang($sosim);
			$mangimg = khongdau($mang);
			$mang_url = str_replace('{TELCO_NAME}', $mang, TELCO_URL);
			$loai = getLoaiSimByCatId($line->cat_id);
			$loai_url = str_replace('{CAT_ID}', $line->cat_id, str_replace('{CAT_NAME}', khongdau($loai), CAT_URL));
			//$giaban = $line->price - 100000;
			if ($line->price <= 0)
				$gia = "(Đã bán)";
            elseif ($line->price == 1)
				$gia = "Vui lòng gọi";
			else {
				//$giaban = $line->price > 500000 ? $line->price - 50000 : $line->price;
				$giaban = $line->price;
				$gia = number_format($giaban, 0, '', '.');
			}
			if ($line->cat_id == SIMDOI_ID) {
				$giongnhau = substr($sosim, -6);
				$sim_url = str_replace('{SIM}', $giongnhau, SIMDOI_URL);
				$sosim = str_replace("+", "<br>", $sosim);
			} else {
				$sim_url = str_replace('{SIM}', $sosim, SIM_URL);
			}
			$simView = $line->simfull;
			?>
            <div class="col-md-3">
                <div class="simhotitem">
                    <a href="<?= $sim_url ?>">
                        <amp-img src="/frontend/icon/icon_<?= $mangimg ?>.png?v=1" width="50"
                                 height="50" alt="{{"Sim ". $mage}}"></amp-img>
                        <p>
                            <span class="ssim"><?= $simView ?></span>
                            <span class="price"><?= $gia ?> đ</span>
                        </p>
                    </a>
                    <a class="hv" href="<?= $sim_url ?>">Đặt mua</a>
                </div>
            </div>
			<?php endforeach; ?>
        </div>
    </div>
	<?php endif; ?>
    <div class="clearfix"></div>
    @if (!empty($widgetsContent))
        @foreach($widgetsContent as $widgetContent)
            {{ Widget::run('gridView', array_merge($widgetContent,['amp' => true])) }}
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
