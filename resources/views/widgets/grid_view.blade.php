@if(!empty($config['listSim']))
    <div class="simhot">
        <h3 class="head-title no-icon"><i class="fa fa-bullhorn" aria-hidden="true"></i> {{$config['title']}}</h3>
        <div class="row col-mar-5">
            @if(!empty($config['listSim'] && is_array($config['listSim'])))
                @php shuffle($config['listSim']); @endphp
                @php $config['listSim'] = array_slice($config['listSim'], 0 ,$config['items']); @endphp
                @foreach($config['listSim'] as $key => $sim)
                    @php
                        /** @var array $sim */
                        $telCo = checkmang($sim['sim']);
                         /** @var string $telCoImage */
                        $telCoImage = khongdau($telCo);
                        /** @var string $classRow */
                        $classRow = $key == 0 && config('domainInfo')['highlights_number'] ? 'col-md-6' : 'col-md-3';
                         /** @var string $classRowItem */
                        $classRowItem = $key == 0 && config('domainInfo')['highlights_number'] ? ' first' : '';
                        /** @var integer $imageSize */
                        $imageSize =  $key == 0 && config('domainInfo')['highlights_number'] ? 50 : 30;
                        /** @var string $simUrl */
                        $simUrl = $sim['sim'];
                    @endphp
                    <div class="{{$classRow}}">
                        <div class="simhotitem {{$classRowItem}}">
                            <a href="{{$simUrl}}">

                                @if($config['amp'])
                                    <amp-img src="/frontend/icon/icon_{{$telCoImage}}.png?v=1"
                                             width="{{$imageSize}}" height="{{$imageSize}}"></amp-img>
                                @else
                                    <img src="/frontend/icon/icon_{{$telCoImage}}.png?v=1">
                                @endif
                                <p>
                                    <span class="ssim">{{$sim['simfull']}}</span>
                                    <span class="price">{{number_format($sim['price'])}} đ</span>
                                </p>
                            </a>
                            <a class="hv" href="{{$simUrl}}">Đặt mua</a>
                        </div>
                    </div>
                @endforeach
                <div class="col-md-3">
                    <div class="simhotitem viewmore">
                        <a href="{{$config['viewMoreUrl']}}">
                            <i class="fa fa-location-arrow fa-2x" aria-hidden="true" style="margin-right: 10px">
                            </i>XEM THÊM</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif
