<?php
/**
 * Created by PhpStorm.
 * User: DUYDUC
 * Date: 16-Jul-19
 * Time: 11:28 AM
 */
$activeDauso = $filterActive['m10so_filter'] != 0 ? $filterActive['m10so_filter'] : '';
$activeMang = $filterActive['telco_filter'] != 0 ? $filterActive['telco_filter'] : '';
$activePrice = $filterActive['price_filter'] !== 0 ? $filterActive['price_filter'] : '';
$activeOrder = $filterActive['giaban_filter'] != 0 ? $filterActive['giaban_filter'] : '';
$linkremoveDauso = $currentUrl . '?m10so_filter=&telco_filter='.$activeMang.'&price_filter='.$activePrice.'&giaban_filter='.$activeOrder.'&filter=true';
$linkremoveMang = $currentUrl . '?m10so_filter='.$activeDauso.'&telco_filter=&price_filter='.$activePrice.'&giaban_filter='.$activeOrder.'&filter=true';
$linkremovePrice = $currentUrl . '?m10so_filter='.$activeDauso.'&telco_filter='.$activeMang.'&price_filter=&giaban_filter='.$activeOrder.'&filter=true';
$linkremoveOrder = $currentUrl . '?m10so_filter='.$activeDauso.'&telco_filter='.$activeMang.'&price_filter='.$activePrice.'&giaban_filter=&filter=true';
// dau so
$dausoFilder = '';
$arrayDauso = ['09', '08', '07', '05', '03'];
$arrayMang = [
    ['name' => 'Viettel', 'value' => 1],
    ['name' => 'Vinaphone', 'value' => 2],
    ['name' => 'Mobifone', 'value' => 3],
    ['name' => 'Vietnamobile', 'value' => 4],
    ['name' => 'Gmobile', 'value' => 5],
    ['name' => 'Itelecom', 'value' => 8],
];
$arrayGia = [
    ['name' => '<1tr', 'value' => '0_1'],
    ['name' => '1-2tr', 'value' => '1_2'],
    ['name' => '2-3tr', 'value' => '2_3'],
    ['name' => '3-5tr', 'value' => '3_5'],
    ['name' => '5-8tr', 'value' => '5_8'],
    ['name' => '8-10tr', 'value' => '8_10'],
    ['name' => '10-15tr', 'value' => '10_15'],
    ['name' => '15-20tr', 'value' => '15_20'],
    ['name' => '20-50tr', 'value' => '20_50'],
    ['name' => '50-100tr', 'value' => '50_100'],
    ['name' => '>100tr', 'value' => '100_0'],
];
switch ($activeMang) {
    case 1:
        $arrayDauso = ['09', '08', '03'];
        break;
    case 2:
        $arrayDauso = ['09', '08'];
        break;
    case 3:
        $arrayDauso = ['09', '08', '07'];
        break;
    case 4:
    case 5:
        $arrayDauso = ['09', '05'];
        break;
    case 8:
        $arrayDauso = ['08'];
        break;
}
switch ($activeDauso) {
    case '09':
        $arrayMang = [
            ['name' => 'Viettel', 'value' => 1],
            ['name' => 'Vinaphone', 'value' => 2],
            ['name' => 'Mobifone', 'value' => 3],
            ['name' => 'Vietnamobile', 'value' => 4],
            ['name' => 'Gmobile', 'value' => 5],
        ];
        break;
    case '08':
        $arrayMang = [
            ['name' => 'Viettel', 'value' => 1],
            ['name' => 'Vinaphone', 'value' => 2],
            ['name' => 'Mobifone', 'value' => 3],
            ['name' => 'Itelecom', 'value' => 8],
        ];
        break;
    case '07':
        $arrayMang = [
            ['name' => 'Mobifone', 'value' => 3],
        ];
        break;
    case '05':
        $arrayMang = [
            ['name' => 'Vietnamobile', 'value' => 4],
            ['name' => 'Gmobile', 'value' => 5],
        ];
        break;
    case '03':
        $arrayMang = [
            ['name' => 'Viettel', 'value' => 1],
        ];
        break;
}
switch ($currentUrl) {
    case 'sim-viettel':
        $arrayDauso = ['09', '08', '03'];
        break;
    case 'sim-vinaphone':
        $arrayDauso = ['09', '08'];
        break;
    case 'sim-mobifone':
        $arrayDauso = ['09', '08', '07'];
        break;
    case 'sim-gmobile':
    case 'sim-vietnamobile':
        $arrayDauso = ['09', '05'];
        break;
    case 'sim-itelecom':
        $arrayDauso = ['08'];
        break;
    case 'sim-luc-quy':
        $arrayGia = [
            ['name' => '200-300tr', 'value' => '200_300'],
            ['name' => '300-500tr', 'value' => '300_500'],
            ['name' => '>500tr', 'value' => '500_0'],
        ];
        break;
    case 'sim-luc-quy-giua':
        $arrayGia = [
            ['name' => '10-15tr', 'value' => '10_15'],
            ['name' => '15-20tr', 'value' => '15_20'],
            ['name' => '20-50tr', 'value' => '20_50'],
            ['name' => '50-100tr', 'value' => '50_100'],
            ['name' => '>100tr', 'value' => '100_0'],
        ];
        break;
    case 'sim-ngu-quy':
        $arrayGia = [
            ['name' => '20-50tr', 'value' => '20_50'],
            ['name' => '50-100tr', 'value' => '50_100'],
            ['name' => '>100tr', 'value' => '100_0'],
        ];
        break;
    case 'sim-ngu-quy-giua':
        $arrayGia = [
            ['name' => '1-2tr', 'value' => '1_2'],
            ['name' => '2-3tr', 'value' => '2_3'],
            ['name' => '3-5tr', 'value' => '3_5'],
            ['name' => '5-8tr', 'value' => '5_8'],
            ['name' => '8-10tr', 'value' => '8_10'],
            ['name' => '10-15tr', 'value' => '10_15'],
            ['name' => '15-20tr', 'value' => '15_20'],
            ['name' => '20-50tr', 'value' => '20_50'],
            ['name' => '50-100tr', 'value' => '50_100'],
            ['name' => '>100tr', 'value' => '100_0'],
        ];
        break;
    case 'sim-tu-quy':
        $arrayGia = [
            ['name' => '3-5tr', 'value' => '3_5'],
            ['name' => '5-8tr', 'value' => '5_8'],
            ['name' => '8-10tr', 'value' => '8_10'],
            ['name' => '10-15tr', 'value' => '10_15'],
            ['name' => '15-20tr', 'value' => '15_20'],
            ['name' => '20-50tr', 'value' => '20_50'],
            ['name' => '50-100tr', 'value' => '50_100'],
            ['name' => '>100tr', 'value' => '100_0'],
        ];
        break;
    case 'sim-tra-gop':
        $arrayGia = [
            ['name' => '10-15tr', 'value' => '10_15'],
            ['name' => '15-20tr', 'value' => '15_20'],
            ['name' => '20-50tr', 'value' => '20_50'],
            ['name' => '50-100tr', 'value' => '50_100'],
            ['name' => '>100tr', 'value' => '100_0'],
        ];
        break;
    case 'sim-gia-re':
        $arrayGia = [
            ['name' => '<1tr', 'value' => '0_1'],
            ['name' => '1-2tr', 'value' => '1_2'],
        ];
        break;
}
foreach ($arrayDauso as $ds) {
    $buildUrl = $currentUrl . '?m10so_filter='.$ds.'&telco_filter='.$activeMang.'&price_filter='.$activePrice.'&giaban_filter='.$activeOrder.'&filter=true';
    $classActive = 'loc_dauso';
    if ($activeDauso == $ds) {
        $classActive .= ' remove_active clearfix';
        $buildUrl = $linkremoveDauso;
    }
    $dausoFilder .= '<a class="'.$classActive.'" href="'.$buildUrl.'">'.$ds.'</a> ';
}
// mang
$mangFilder = '';
foreach ($arrayMang as $ds) {
    $buildUrl = $currentUrl . '?m10so_filter='.$activeDauso.'&telco_filter='.$ds['value'].'&price_filter='.$activePrice.'&giaban_filter='.$activeOrder.'&filter=true';
    $classActive = 'loc_mang';
    if ($activeMang == $ds['value']) {
        $classActive .= ' remove_active clearfix';
        $buildUrl = $linkremoveMang;
    }
    $mangFilder .= '<a class="'.$classActive.'" href="'.$buildUrl.'">'.$ds['name'].'</a> ';
}
// gia
$giaFilder = '';
if (!empty($arrayGia)) {
    foreach ($arrayGia as $ds) {
        $buildUrl = $currentUrl . '?m10so_filter='.$activeDauso.'&telco_filter='.$activeMang.'&price_filter='.$ds['value'].'&giaban_filter='.$activeOrder.'&filter=true';
        $classActive = 'loc_gia';
        if ($activePrice == $ds['value']) {
            $classActive .= ' remove_active clearfix';
            $buildUrl = $linkremovePrice;
        }
        $giaFilder .= '<a class="'.$classActive.'" href="'.$buildUrl.'">'.$ds['name'].'</a> ';
    }
}
?>
<div class="box-filter">
    <h3 class="h-title">Bộ lọc sim</h3>
    <?php if (empty($hideDauso) && $dausoFilder != '') : ?>
    <div class="boxgoiy loc_dauso">
        <span class="stitle">Chọn đầu số:</span>
        <?= $dausoFilder ?>
    </div>
    <?php endif; ?>
    <?php if (empty($hideMang) && $mangFilder != '') : ?>
    <div class="boxgoiy loc_mang">
        <span class="stitle">Chọn mạng:</span>
        <?= $mangFilder ?>
    </div>
    <?php endif; ?>
    <?php if (empty($hidePrice) && $giaFilder != '') : ?>
    <div class="boxgoiy loc_gia">
        <span class="stitle">Chọn giá:</span>
        <?=$giaFilder ?>
    </div>
    <?php endif; ?>
</div>
