<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('getdata')) {
    /**
    * Grab data input
    * 
    * Data di ambil dari url atau dari header body
    *
    * @param string $method memilih data dari method yang di request
    * @param bool $xss Filter security Xss
    * @return mixed[]
    */
    function &getdata($method='', $xss=true) {
        $data=array();
        $CI =& get_instance();
        if($method=='') $method=$CI->input->method();
        switch($method) {
            case 'get':
            case 'delete':
                $cururl=explode('/',$CI->uri->uri_string()); 
                $class=$CI->router->fetch_class();
                $method=$CI->router->fetch_method();
                $get=0;
                for($i=1,$n=count($cururl);$i<$n;$i++) {
                    if($cururl[$i-1]==$class && $cururl[$i]==$method) {
                        $get=$i+2;
                        break;
                    }
                }
                if($get>0) {
                    $cururl = $CI->uri->uri_to_assoc($get);
                    if(count($cururl)>0) {
                        if(!in_array(null, $cururl, true)) $data=($xss)?$CI->security->xss_clean($cururl):$cururl;
                    }
                }
                unset($cururl);
            break;
            case 'post':
            case 'put':
                $find=false;
                $json = file_get_contents('php://input'); 
                if($json!='') {
                    if($json = json_decode($json,true)) {
                        $find=true;
                        $data=($xss)?$CI->security->xss_clean($json):$json;
                    }
                }
                unset($json);
                if(!$find) {
                    if($post = $CI->input->post(NULL, $xss)) {
                        $data=$post;
                        unset($post);
                    }
                }
            break;
        }
        return $data;
    }
}

if (!function_exists('get_real_ip')) {
    /**
    * Get IP
    * 
    * Mendapatkan IP request dari user
    *
    * @return string
    */
    function get_real_ip() {
        $ip = "0.0.0.0";
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_REAL_IP'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}

if (!function_exists('hsl2rgb')) {
    /**
    * Generate HSL to RGB
    *
    * @param int $h Hue is a degree on the color wheel from 0 to 360. 0 is red, 120 is green, 240 is blue.
    * @param int $s Saturation is a percentage value; 0% means a shade of gray and 100% is the full color.
    * @param int $l Lightness is also a percentage; 0% is black, 100% is white.
    * @return array(rgb)
    */
    function hsl2rgb ($h, $s, $l) {
        $h /= 60;
        if ($h < 0) $h = 6 - fmod(-$h, 6);
        $h = fmod($h, 6);

        $s = max(0, min(1, $s / 100));
        $l = max(0, min(1, $l / 100));

        $c = (1 - abs((2 * $l) - 1)) * $s;
        $x = $c * (1 - abs(fmod($h, 2) - 1));

        if ($h < 1) {
            $r = $c;
            $g = $x;
            $b = 0;
        } elseif ($h < 2) {
            $r = $x;
            $g = $c;
            $b = 0;
        } elseif ($h < 3) {
            $r = 0;
            $g = $c;
            $b = $x;
        } elseif ($h < 4) {
            $r = 0;
            $g = $x;
            $b = $c;
        } elseif ($h < 5) {
            $r = $x;
            $g = 0;
            $b = $c;
        } else {
            $r = $c;
            $g = 0;
            $b = $x;
        }

        $m = $l - $c / 2;
        $r = round(($r + $m) * 255);
        $g = round(($g + $m) * 255);
        $b = round(($b + $m) * 255);

        return ['r' => $r, 'g' => $g, 'b' => $b];

    }
}

?>