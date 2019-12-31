<?php
function isAdmin() {
    $user = Sentinel::getUser();
    $admin = Sentinel::findRoleBySlug('superadmin');
    if ($user && $user->inRole($admin)) {
        return true;
    }

    return false;
}
function isManager(){
	$user = Sentinel::getUser();
	$admin = Sentinel::findRoleBySlug('superadmin');
	$domainManager = Sentinel::findRoleBySlug('domainManager');
	if ($user && ($user->inRole($admin)) || $user->inRole($domainManager)) {
		return true;
	}

	return false;
}
function isMobile() {
    $mbDetect = new \App\Extensions\Mobile_Detect();
    return $mbDetect->isMobile() && !$mbDetect->isTablet();
}
function isTablet() {
    $mbDetect = new \App\Extensions\Mobile_Detect();
    return $mbDetect->isTablet();
}
function errors_for($attribute, $errors)
{
    return $errors->first($attribute, '<p class="text-danger">:message</p>');
}

function set_active($path, $active = 'active')
{
    // return Request::is($path) || Request::is($path . '/*') ? $active: '';
    return Request::is($path) || Request::is($path . '/*') ? $active : '';
}

function set_active_admin($path, $active = 'active')
{
    return Request::is($path) ? $active : '';
}

function utf8Substr($str, $len, $start = 0)
{
    return preg_replace('#^(?:[x00-x7F]|[xC0-xFF][x80-xBF]+){0,' . $start . '}((?:[x00-x7F]|[xC0-xFF][x80-xBF]+){0,' . $len . '}).*#s', '$1', $str);
}

function in_banned_words($content)
{
    $banned = array("địt", "đéo", "đít", "lồn", "cứt", "đái", "ỉa", "mịe", "buồi", "chán", "chim", "điên", "deo", "dit", "lon", "cut", "dai", "ia", "me", "mie", "buoi", "chan", "dien");
    if (is_array($banned)) {
        foreach ($banned as $key => $value) {
            $pos = strpos($content, $value);
            if ($pos !== false)
                return true;
        }
    }
    return false;
}

function wraptext($str, $limit, $break = " ")
{
    $limit = (int)$limit;
    return $limit > 0 ? preg_replace(
        '/(\S{' . ($limit + 1) . ',})/e', 'wordwrap( \'\1\', ' . $limit . ', \'' . $break . '\', TRUE )', $str) : $str;
}

function neat_trim($str, $n, $delim = '…')
{
    $len = strlen($str);
    if ($len > $n) {
        preg_match('/(.{' . $n . '}.*?)\b/', $str, $matches);
        return rtrim($matches[1]) . $delim;
    } else {
        return $str;
    }
}

function khongdau($str)
{
    $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
    $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
    $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
    $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
    $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
    $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
    $str = preg_replace("/(đ)/", 'd', $str);
    $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
    $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
    $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
    $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
    $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
    $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
    $str = preg_replace("/(Đ)/", 'D', $str);
    $str = str_replace(" ", "-", str_replace("&amp;*#39;", "", str_replace("/", "-", str_replace("?", "-", $str))));
    $str = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $str);
    $str = strtolower(trim($str, '-'));
    $str = preg_replace("/[\/_|+ -]+/", '-', $str);
    return $str;
}

function get_cat_id($phone)
{
    $pattern = "/(2628|1368|1618|8683|5239|9279|3937|3938|3939|3333|8386|8668|4648|8888|4078|6666|3468|1668|7939|7838|7878|2879|1102|6789|6758|3737|4404)$/"; //Số độc
    if (preg_match($pattern, $phone))
        return 78;
    else
        $pattern = "/(00000|11111|22222|33333|44444|55555|66666|77777|88888|99999)$/"; //VIP
    if (preg_match($pattern, $phone))
        return 82;
    else
        $pattern = "/(0000|1111|2222|3333|4444|5555|6666|7777|8888|9999)$/"; //Tứ quý
    if (preg_match($pattern, $phone))
        return 68;
    else
        $pattern = "/(((\d{3})\\3)|([0-9](\d{2})[0-9]\\5)|(([0-9])\\7[0-9]([0-9])\\8[0-9])|((\d{2})\\10\\10)|(([0-9])\\12([0-9])\\13([0-9])\\14))$/"; //Taxi abc.abc, acc.bcc, aac.bbc, ab.ab.ab, aa.bb.cc
    if (preg_match($pattern, $phone))
        return 74;
    else
        $pattern = "/(000|111|222|333|444|555|666|777|888|999)$/"; //Tam hoa
    if (preg_match($pattern, $phone))
        return 80;
    else
        $pattern = "/(1268|1286|1186|2286|3386|4486|5586|6686|8886|9986|1168|2268|3368|4468|5568|6668|8868|9968|68168|68268|68368|68468|68568|68668|68768|68868|68968|861186|862286|863386|864486|865586|866686|867786|868886|869986|688|668|886|866)$/"; //Lộc phát
    if (preg_match($pattern, $phone))
        return 73;
    else
        $pattern = "/(3939|3979|7939|7979|6879|6679|8679|3339|779|3878|7838|6878|3338|3839|3879|7879|5679|3679)$/"; //Thần tài
    if (preg_match($pattern, $phone))
        return 72;
    else
        $pattern = "/(789|678|567|456|345|234|123|012)$/"; //Tiến lên
    if (preg_match($pattern, $phone))
        return 81;
    else
        $pattern = "/(5689|6689|6696|8898|8386|8689|8286|5569|1468|8699|8698|6698)$/"; //Dễ nhớ
    if (preg_match($pattern, $phone))
        return 81;
    else
        $pattern = "/((([0-9])([0-9])\4\3)|(([0-9])[0-9]\6))$/"; //Gánh đảo abba, axa
    if (preg_match($pattern, $phone))
        return 79;
    else
        $pattern = "/(((\d{2})\\3)|(([0-9])\\5([0-9])\\6))$/"; //Lặp kép aabb, abab
    if (preg_match($pattern, $phone))
        return 67;
    else
        $pattern = "/(((((0[1-9]|[12][0-9]|3[01])(0?[1-9]|1[012]))|((0?[1-9]|[12][0-9]|3[01])(0[1-9]|1[012])))[5-9][0-9])|((19[5-9][0-9])|(20[0-1][0-9])))$/"; //Năm sinh
    if (preg_match($pattern, $phone))
        return 77;
    else
        return 84; //Tự chọn
}

function startsWith($haystack, $needle)
{
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

function endsWith($haystack, $needle)
{
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
}

function check_ten_mang($mang)
{
    $LOAIMANG = config('global')['LOAIMANG'];
    $ok = 0;
    $s_mang = strtolower($mang);
    foreach ($LOAIMANG as $key => $value) {
        if ($value['tenmang'] == $s_mang) {
            $ok = $key;
            break;
        }
    }
    return $ok;
}

function getLoaiSimByCatId($id)
{
    $LOAISIM = config('global')['LOAISIM'];
    if (isset($LOAISIM[$id])) {
        return $LOAISIM[$id];
    }

    return 'Sim tự chọn';
}

function getCatIdByAlias($alias)
{
    $LOAISIM = config('global')['LOAISIM'];
    foreach ($LOAISIM as $key => $loai) {
        if (khongdau($loai) == $alias) {
            return ['cid' => $key, 'name' => $loai];
        }
    }
    return ['cid' => 84, 'name' => 'Sim tự chon'];
}
function get_cat_id2($phone) {

    $pattern = "/(000000000|111111111|222222222|333333333|444444444|555555555|666666666|777777777|888888888|999999999)$/"; //Vãi Quý
    if (preg_match($pattern, $phone)) {
        return 82;
    } else {
        $pattern = "/(00000000|11111111|22222222|33333333|44444444|55555555|66666666|77777777|88888888|99999999)$/"; //Bát Quý
        if (preg_match($pattern, $phone)) {
            return 82;
        } else {
            if (preg_match($pattern, $phone))
                return 78;
            $pattern = "/(0000000|1111111|2222222|3333333|4444444|5555555|6666666|7777777|8888888|9999999)$/"; //Thất Quý
            if (preg_match($pattern, $phone)) {
                return 82;
            } else {
                $pattern = "/(000000|111111|222222|333333|444444|555555|666666|777777|888888|999999)$/"; //Lục Quý
                if (preg_match($pattern, $phone)) {
                    return 100;
                } else {
                    $pattern = "/(00000|11111|22222|33333|44444|55555|66666|77777|88888|99999)$/"; //Ngũ Quý
                    if (preg_match($pattern, $phone)) {
                        return 99;
                    } else {
                        $pattern = "/(0000|1111|2222|3333|4444|5555|6666|7777|8888|9999)$/"; //Tứ quý
                        if (preg_match($pattern, $phone)) {
                            return 68;
                        } else {
                            $pattern = "/(.*)(000000|111111|222222|333333|444444|555555|666666|777777|888888|999999)(.*)/"; //Lục Quý giữa
                            if (preg_match($pattern, $phone)) {
                                return 105;
                            } else {
                                $pattern = "/(.*)(00000|11111|22222|33333|44444|55555|66666|77777|88888|99999)(.*)/"; //Ngũ Quý giữa
                                if (preg_match($pattern, $phone)) {
                                    return 104;
                                } else {
                                    $pattern = "/(.*)(0000|1111|2222|3333|4444|5555|6666|7777|8888|9999)(.*)/"; //Tứ quý giữa
                                    if (preg_match($pattern, $phone)) {
                                        return 103;
                                    } else {

                                    }
                                }
                            }

                            $pattern = "/(000|111|222|333|444|555|666|777|888|999)$/"; //Tam hoa
                            if (preg_match($pattern, $phone)) {
                                $pattern = "/(000|111|222|333|444|555|666|777|888|999)$/"; //Tam hoa kép
                                if (preg_match($pattern, substr($phone, 0, -3)))
                                    return 102;
                                return 80;
                            }
                            else {
                                $pattern = "/(((\d{3})\\3)|((\d{2})\\5\\5)|((\d{4})\\7)|(([0-9])\\9\\9\\9([0-9])\\10\\10\\10))$/"; //Taxi ABC.ABC, AB.AB.AB, ABCD.ABCD, AAAA.BBBB
                                if (preg_match($pattern, $phone))
                                    return 74;
                                $pattern = "/(66|88|68|86|88|69|96)$/"; //Lộc phát
                                if (preg_match($pattern, $phone))
                                    return 73;
                                $pattern = "/(39|79)$/"; //Thần tài
                                if (preg_match($pattern, $phone))
                                    return 72;
                                $pattern = "/(38|78)$/"; //Ông địa
                                if (preg_match($pattern, $phone))
                                    return 70;
                                $pattern = "/((([0-9])([0-9])\\4\\3)|(([0-9])([0-9])([0-9])\\8\\7\\6))$/"; //Gánh đảo AB.BA, ABC.CBA
                                if (preg_match($pattern, $phone))
                                    return 79;
                                $pattern = "/(((\d{2})\\3)|(([0-9])\\5([0-9])\\6))$/"; //Lặp kép AB.AB, AA.BB
                                if (preg_match($pattern, $phone))
                                    return 67;
                                $arrc = str_split($phone);
                                $len = count($arrc) - 1;
                                if (($arrc[$len] == $arrc[$len - 1]) && $arrc[$len - 2] == $arrc[$len - 3]) { //kep
                                    return 120;
                                    if (($arrc[$len - 4] == $arrc[$len - 5]) && $arrc[$len - 2] == $arrc[$len - 3]) {
                                        if (($arrc[$len - 4] == $arrc[$len - 5]) && $arrc[$len - 6] == $arrc[$len - 7]) { //kep 4
                                            return 122;
                                        } else { //kep 3
                                            return 121;
                                        }
                                    }
                                } else
                                    if (($arrc[$len] == $arrc[$len - 2]) && $arrc[$len - 1] == $arrc[$len - 3]) { //lap
                                        return 123;
                                        if (($arrc[$len - 4] == $arrc[$len - 2]) && $arrc[$len - 5] == $arrc[$len - 3]) {
                                            if (($arrc[$len - 4] == $arrc[$len - 6]) && $arrc[$len - 5] == $arrc[$len - 7]) { //lap 4
                                                return 125;
                                            } else { //lap 3
                                                return 124;
                                            }
                                        }
                                    }
                            }
                        }
                    }
                }
            }
        }
    }

    $pattern = "/(789|678|567|456|345|234|123|012)$/"; //Tiến lên A(A+1)(A+2), AB.A(B+1).A(B+2), AB.(A+1)B.(A+2)B ??????????
    if (preg_match($pattern, $phone))
        return 81;
    $pattern = "/((([0-9])\\3[0-9]\\3\\3[0-9])|(([0-9])([0-9])\\6\\5([0-9])\\7)|(([0-9])\\9[0-9]([0-9])\\10[0-9])|([0-9]([0-9])\\12[0-9]\\12\\12))$/"; //Dễ nhớ AAB.AAC, ABB.ACC, AAB.CCD, ABB.CBB
    if (preg_match($pattern, $phone))
        return 76;
    $pattern = "/(0[1-9]|[1-2][0-9]|31(?!(?:0[2469]|11))|30(?!02))(0[1-9]|1[0-2])(((19)?[5-9][0-9])|((20)?[0-1][0-9]))$/"; //Năm sinh
    if (preg_match($pattern, $phone))
        return 77;
    $pattern = "/^(0913|0903|0983)/"; //Đầu cổ 0913, 0903, 0983
    if (preg_match($pattern, $phone))
        return 106;
    $pattern = "/(8683|1515|2626|2628|1368|1618|8683|5239|9279|3937|3938|3939|8386|8668|4648|4078|3468|1668|7939|7838|7878|2879|1102|6789|6758|3737|4404|49532626|5239|9279|3937|39|38|3939|3333|8386|8668|4648|4078|3468|6578|6868|1668|8686|73087|1122|6789|6758|0607|0378|8181|3737|6028|7762|3609|8163|9981|7749|6612|5510|1257|0908|8906|1110|7749|2204|4444|8648|0404|0805|3546|5505|2306|1314|5031|2412|1920227|151618|181818|191919|2204|1486|01234|456)$/"; //Số độc
    if (preg_match($pattern, $phone))
        return 78;
    return 84; //Tự chọn
    return tinhnguhanh($phone);
}
function checkmang($simso){
    $simso = $simso[0] == '0' ? $simso : '0'.$simso;
    $mang[1]['tenmang']="viettel";
    $mang[1]['dauso']="0961,0962,0963,0964,0965,0966,0967,0968,0969,0971,0972,0973,0974,0975,0976,0977,0978,0979,0981,0982,0983,0984,0985,0986,0987,0988,0989,096,097,098,086,0162,0163,0164,0165,0166,0167,0168,0169,032, 033, 034, 035, 036, 037, 038, 039";
    $mang[1]['tukhoa']="viettel,vietel,viettell,viet tel";

    $mang[2]['tenmang']="vinaphone";
    $mang[2]['dauso']="094,088,091,0123,0124,0125,0127,0129,0941,0942,0943,0944,0945,0946,0947,0948,0949,0911,0912,0913,0914,0915,0916,0917,0918,0919,083,084,085,081,082,088";
    $mang[2]['tukhoa']="vina,vinaphone,vinafone";

    $mang[3]['tenmang']="mobifone";
    $mang[3]['dauso']="090,093,0901,0902,0903,0904,0905,0906,0907,0908,0909,0931,0932,0933,0934,0935,0936,0937,0938,0939,089,0120,0121,0122,0126,0128,070,079,077,076,078";
    $mang[3]['tukhoa']="mobi,mobifone,mobiphone,mobi phone,mobi fone";

    $mang[4]['tenmang']="vietnamobile";
    $mang[4]['dauso']="092,0186,0188,056,058,052";
    $mang[4]['tukhoa']="vietnamobile,vietnammobile,vietnam mobile";

    $mang[5]['tenmang']="gmobile";
    $mang[5]['dauso']="099,0199,059";
    $mang[5]['tukhoa']="gmobile,Gmobile";

    $mang[6]['tenmang']="sfone";
    $mang[6]['dauso']="095";
    $mang[6]['tukhoa']="sfone,s fone,s phone,sphone";

    $mang[7]['tenmang']="máy bàn";
    $mang[7]['dauso']="024,028";
    $mang[7]['tukhoa']="may ban,co dinh,homephone,gphone, home phone,g phone";

    $mang[8]['tenmang']="itelecom";
    $mang[8]['dauso']="087";
    $mang[8]['tukhoa']="itelecom";

    $tenmang="khongxacdinh";
    $dauso4 = substr($simso,0,3);

    foreach($mang as $key=>$value){
        if((strpos($value['dauso'],$dauso4) !== false)){
            $tenmang=$value['tenmang'];
            break;
        }
    }
    return $tenmang;
}
function convertmang($m) {
    $telcom = 0;
    switch ($m) {
        case 'viettel':
            $telcom = 1;
            break;
        case 'vinaphone':
            $telcom = 2;
            break;
        case 'mobifone':
            $telcom = 3;
            break;
        case 'vietnamobile':
            $telcom = 4;
            break;
        case 'gmobile':
            $telcom = 5;
            break;
        case 'sfone':
            $telcom = 6;
            break;
        case 'mayban':
            $telcom = 7;
            break;
        default:
            $telcom = 0;
    }
    return $telcom;
}
function substr_word($string, $maxlength){
    if (strlen($string) < $maxlength) return $string;
    $string = substr($string, 0, $maxlength);
    $rpos = strrpos($string,' ');
    if ($rpos > 0) $string = substr($string, 0, $rpos);
    return $string;
}
function searchArray($array, $key, $value)
{
    $results = array();

    if (is_array($array))
    {
        if (isset($array[$key]) && $array[$key] == $value)
            $results[] = $array;

        foreach ($array as $subarray)
            $results = array_merge($results, searchArray($subarray, $key, $value));
    }

    return $results;
}
function createImage($sosim, $simfull, $gia, $mang, $loaisim) {
	$domainInfo = config('domainInfo');
    if ($mang != 'sfone' && $mang != 'máy bàn') {
        $photo_to_paste = public_path('frontend/condau/giaydan.jpg');  //image 321 x 400
        $condau = public_path() . '/frontend/condau/condautopsim.png';  //image 321 x 400
		if (!empty($domainInfo['condau'])) {
			$condau = public_path() . $domainInfo['condau'];  //image 321 x 400
		}
        $condau = imagecreatefrompng($condau);
        $white_image = public_path() .'/frontend/condau/'. $mang.".jpg"; //873 x 622

        $im = imagecreatefromjpeg($white_image);
        $condicion = getimagesize($photo_to_paste); // image format?
        if($condicion[2] == 1) //gif
            $im2 = imagecreatefromgif("$photo_to_paste");
        if($condicion[2] == 2) //jpg
            $im2 = imagecreatefromjpeg("$photo_to_paste");
        if($condicion[2] == 3) //png
            $im2 = imagecreatefrompng("$photo_to_paste");
        $color = imagecolorallocate($im2, 0, 0, 0);
        $font = public_path() . '/frontend/condau/arialbd.ttf';
        imagettftext($im2, 48, 0, 20, 70, $color, $font,$simfull);
        imagettftext($im2, 28, 0, 20, 105, $color, $font, $gia);
		if (!empty($domainInfo['domain_name'])) {
			imagettftext($im2, 28, 0, 20, 145, $color, $font,'Kho: ' . $domainInfo['domain_name']);
		} else {
			imagettftext($im2, 28, 0, 20, 145, $color, $font,'Kho: Sim số đẹp');
		}
        imagettftext($im2, 28, 0, 20, 185, $color, $font, $loaisim);
        $condau = imagerotate($condau, 10-substr($sosim, 4,1), imagecolorallocatealpha($im2, 12, 12, 12, 127));

        if(!isset($_GET['nonbrandname'])){
            imagecopy($im2, $condau, 120+10*(substr($sosim, 6,1)), (imagesy($im)/2)-(imagesy($im2)/2)+(10-substr($sosim, 7,1))-10, 0, 0, imagesx($condau), imagesy($condau));
        }else{}
        $im2 = imagerotate($im2, $sosim%12-6, imagecolorallocatealpha($im2, 0, 0, 0, 127));
        imagecopy($im, $im2, 410+(10-substr($sosim, 5,1)), (imagesy($im)/2)-(imagesy($im2)/2)+(10-substr($sosim, 6,1)), 0, 0, imagesx($im2), imagesy($im2));
        $i=substr($sosim, 3,4)%10+1;
        for(;$i>0;$i--){
            switch ($i) {
                case '1':
                    $im2 = imagecreatefrompng(public_path() . "/frontend/condau/vet1.png");
                    $im2 = imagerotate($im2, $sosim%350, imagecolorallocatealpha($im2, 0, 0, 0, 127));
                    imagecopy($im, $im2, (substr($sosim, 5,3))-450, (imagesy($im)/2)-(imagesy($im2)/2)+(10-substr($sosim, 6,1)), 0, 0, imagesx($im2), imagesy($im2));
                    break;
                case '2':
                    break;
                case '3':
                    break;
                default: break;
            }
        }
        $im2 = imagecreatetruecolor(500, 332);
        imagecopyresampled($im2, $im, 0, 0, 0, 0, 500, 332, 1000, 644);
        $im = $im2;
    } else {
        $im = imagecreatefromjpeg(public_path() . '/frontend/condau/simthanglong.jpg');
        $im2 = imagecreatetruecolor(300, 199);
        imagecopyresampled($im2, $im, 0, 0, 0, 0, 300, 199, 1000, 644);
        $im = $im2;
    }
    header('Pragma: public');
    header('Cache-Control: max-age=86400');
    header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + 86400));
    header('Content-Type: image/jpeg');
    try{
	    imagejpeg($im);
	    imagedestroy($im);
	    imagedestroy($im2);
    }catch (Exception $e){

    }
}

function getHotLine($hotlineList){
	if (!empty($hotlineList)) {
		$randHotLineIndex = array_rand($hotlineList);
		return $hotlineList[$randHotLineIndex];
	}
	return [
		'hot' => '',
		'name' => ''
	];
}
