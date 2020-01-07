@extends('master')

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
                        <img src="/frontend/icon/icon_<?= $mangimg ?>.png?v=1">
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
    <!-- SIM HOT -->
    <?php if (!empty($listUuTien['vip'])) : ?>
    <div class="simhot">
        <h3 class="head-title no-icon"><i class="fa fa-bullhorn" aria-hidden="true"></i> SIM DOANH NHÂN</h3>
        <div class="row col-mar-5">
            <?php
            shuffle($listUuTien['vip']);
            $listViettel = array_slice($listUuTien['vip'], 0 ,16);foreach ($listViettel as $key => $simvietel) :
            $mang = checkmang($simvietel['sim']);
            $mangimg = khongdau($mang);
            $classRow = $key == 0 ? 'col-md-6' : 'col-md-3';
            $classRowItem = $key == 0 ? ' first' : '';
            $sim_url = $simvietel['sim'];
            ?>
            <div class="<?= $classRow ?>">
                <div class="simhotitem<?= $classRowItem ?>">
                    <a href="<?= $sim_url ?>">
                        <img src="/frontend/icon/icon_<?= $mangimg ?>.png?v=1">
                        <p>
                            <span class="ssim"><?= $simvietel['simfull'] ?></span>
                            <span class="price"><?= number_format($simvietel['price']) ?> đ</span>
                        </p>
                    </a>
                    <a class="hv" href="<?= $sim_url ?>">Đặt mua</a>
                </div>
            </div>
            <?php endforeach; ?>
            <div class="col-md-3">
                <div class="simhotitem viewmore">
                    <a href="/sim-vip"><i class="fa fa-location-arrow fa-2x" aria-hidden="true" style="margin-right: 10px"></i>XEM THÊM</a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <div class="clearfix"></div>
    <!-- SIM VIETEL -->
    <?php if (!empty($listUuTien[1])) : ?>
    <div class="simhot">
        <h3 class="head-title no-icon"><i class="fa fa-bullhorn" aria-hidden="true"></i> SIM VIETTEL</h3>
        <div class="row col-mar-5">
            <?php
            shuffle($listUuTien[1]);
            $listViettel = array_slice($listUuTien[1], 0 ,16);foreach ($listViettel as $key => $simvietel) :
            $mang = checkmang($simvietel['sim']);
            $mangimg = khongdau($mang);
            $classRow = $key == 0 ? 'col-md-6' : 'col-md-3';
            $classRowItem = $key == 0 ? ' first' : '';
            $sim_url = $simvietel['sim'];
            ?>
            <div class="<?= $classRow ?>">
                <div class="simhotitem<?= $classRowItem ?>">
                    <a href="<?= $sim_url ?>">
                        <img src="/frontend/icon/icon_<?= $mangimg ?>.png?v=1">
                        <p>
                            <span class="ssim"><?= $simvietel['simfull'] ?></span>
                            <span class="price"><?= number_format($simvietel['price']) ?> đ</span>
                        </p>
                    </a>
                    <a class="hv" href="<?= $sim_url ?>">Đặt mua</a>
                </div>
            </div>
            <?php endforeach; ?>
            <div class="col-md-3">
                <div class="simhotitem viewmore">
                    <a href="/sim-viettel"><i class="fa fa-location-arrow fa-2x" aria-hidden="true" style="margin-right: 10px"></i>XEM THÊM</a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <div class="clearfix"></div>
    <!-- SIM VINAPHONE -->
    <?php if (!empty($listUuTien[2])) : ?>
    <div class="simhot">
        <h3 class="head-title no-icon"><i class="fa fa-bullhorn" aria-hidden="true"></i> SIM VINAPHONE</h3>
        <div class="row col-mar-5">
            <div class="row">
    <div class="table-responsive mt-1">
                <table class="table-custom-1 table table-bordered table-striped border-0 font-weight-500">
            <thead>
                <tr>
             <th class="text-center border-white d-none d-md-table-cell" scope="col">STT</th>
            <th class="text-center border-white" scope="col">Sim số</th>
            <th class="text-center border-white" scope="col">Giá bán</th>
            <th class="text-center border-white d-none d-md-table-cell" scope="col">Mạng</th>
            
            <th class="text-center border-white" scope="col">Đặt mua</th>
           
        </tr>
    </thead>
            <tbody>
                 <?php
            shuffle($listUuTien[2]);
            $listViettel = array_slice($listUuTien[2], 0 ,16);foreach ($listViettel as $key => $simvietel) :
            $mang = checkmang($simvietel['sim']);
            $mangimg = khongdau($mang);
            $classRow = $key == 0 ? 'col-md-6' : 'col-md-3';
            $classRowItem = $key == 0 ? ' first' : '';
            $sim_url = $simvietel['sim'];
            ?>

    @if(!empty($listViettel))
      
         
           <tr>
               <th class="text-center align-middle d-none d-md-table-cell" scope="row"><span class="stt">{{ $offsets + $key + 1 }}</span></th>
                <td class="text-center text-nowrap text-center align-middle">
                    <a class="text-danger font-weight-bold fs-120" href="<?= $sim_url ?>"><?= $simvietel['simfull'] ?></a>
                </td>
                <td class="text-center align-middle"><?= number_format($simvietel['price']) ?> đ</td>
                <td class="text-center align-middle d-none d-md-table-cell">
                    <div class="mang">
                     <img src="/frontend/icon/icon_<?= $mangimg ?>.png?v=1">
                    </div>
                </td>
               
                <td class="text-nowrap text-center align-middle">
                    <a class="btn btn-warning btn-sm" href="<?= $sim_url ?>" rel="nofollow">Mua ngay</a>
            </td>
        </tr>
       <?php endforeach; ?>
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
           </div>
          
        </div>
    </div>
    <?php endif; ?>
    <div class="clearfix"></div>
    <!-- SIM MOBIFONE -->
    <?php if (!empty($listUuTien[3])) : ?>
    <div class="simhot">
        <h3 class="head-title no-icon"><i class="fa fa-bullhorn" aria-hidden="true"></i> SIM MOBIFONE</h3>
        <div class="row col-mar-5">
            <?php
            shuffle($listUuTien[3]);
            $listViettel = array_slice($listUuTien[3], 0 ,16);foreach ($listViettel as $key => $simvietel) :
            $mang = checkmang($simvietel['sim']);
            $mangimg = khongdau($mang);
            $classRow = $key == 0 ? 'col-md-6' : 'col-md-3';
            $classRowItem = $key == 0 ? ' first' : '';
            $sim_url = $simvietel['sim'];
            ?>
            <div class="<?= $classRow ?>">
                <div class="simhotitem<?= $classRowItem ?>">
                    <a href="<?= $sim_url ?>">
                        <img src="/frontend/icon/icon_<?= $mangimg ?>.png?v=1">
                        <p>
                            <span class="ssim"><?= $simvietel['simfull'] ?></span>
                            <span class="price"><?= number_format($simvietel['price']) ?> đ</span>
                        </p>
                    </a>
                    <a class="hv" href="<?= $sim_url ?>">Đặt mua</a>
                </div>
            </div>
            <?php endforeach; ?>
            <div class="col-md-3">
                <div class="simhotitem viewmore">
                    <a href="/sim-mobifone"><i class="fa fa-location-arrow fa-2x" aria-hidden="true" style="margin-right: 10px"></i>XEM THÊM</a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <div class="clearfix"></div>
    <?php if (!isMobile()) : ?>
    @if (!empty($web_foot))
        <div class="seo-box">
            <div class="onheadfoot">{!! $web_foot !!}</div>
        </div>
    @endif
    <?php endif; ?>
@endsection
