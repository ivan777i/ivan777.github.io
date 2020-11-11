<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
Module Helper
Desc: Global Functions for API
*/
if(!function_exists('respon')){
    function respon($data = ''){
        $CI  =& get_instance();
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}

if(!function_exists('send_request')){
    function send_request(&$url, &$params, $par = '', $header = array()){
        $CI  =& get_instance();
        $response = jsonreq($url, json_encode($params), $header);
        return $response;
    }
}

if(!function_exists('send_get')){
    function send_get(&$url, &$params, $par = 'default'){
        $CI  =& get_instance();
        $gurl = $url.'?';
        $temp=array();
        foreach($params as $key=>$val) $temp[]=$key.'='.urlencode($val);
        $gurl.=implode('&',$temp);
        $response = getreq($gurl, json_encode($params));
        return $response;
    }
}

if (!function_exists('jsonreq')) {
    function jsonreq($url,$data, $headers = array(),$co='') {
        $ch = curl_def($co);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $data);
        $header=array('Content-Type:application/json');
        foreach($headers as $res) $header[]=$res;
        curl_setopt ($ch, CURLOPT_HTTPHEADER, $header);
        $get = curl_exec ($ch);	
        $response = array(
            'status' => 'failed',
            'real' => $get,
            'decoded' => array()
        );
        if(curl_errno($ch) == 0){
            $response = array(
                'status' => 'success',
                'real' => $get,
                'decoded' => json_decode($get,true)
            );
        }
        else if(curl_errno($ch) == 28){
            $response = array(
                'status' => 'timeout',
                'real' => '',
                'decoded' => array()
            );
        }
        curl_close ($ch);
        return $response;
    }
}

if (!function_exists('getreq')) {
    function getreq($url, $type='application/json', $headers = array(),$co='') {
        $ch = curl_def($co);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $header=array('Content-Type:'.$type);
        foreach($headers as $res) $header[]=$res;
        curl_setopt ($ch, CURLOPT_HTTPHEADER, $header);
        $get = curl_exec ($ch);	
        $response = array(
            'status' => 'failed',
            'real' => $get,
            'decoded' => json_decode($get, true)
        );
        if(curl_errno($ch) == 0){
            $response = array(
                'status' => 'success',
                'real' => $get,
                'decoded' => json_decode($get, true)
            );
        }
        else if(curl_errno($ch) == 28){
            $response = array(
                'status' => 'timeout',
                'real' => '',
                'decoded' => array()
            );
        }
        curl_close ($ch);
        return $response;
    }
}

if (!function_exists('xmlreq')) {
    function xmlreq($url,$data, $headers = array(), $co='') {
        $ch = curl_def($co);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $header=array('Content-Type:application/xml');
        foreach($headers as $res) $header[]=$res;
        curl_setopt ($ch, CURLOPT_HTTPHEADER, $header);
        $get = curl_exec ($ch);	
        $response = array(
            'status' => 'failed',
            'real' => $get,
            'decoded' => json_decode($get, true)
        );
        if(curl_errno($ch) == 0){
            $response = array(
                'status' => 'success',
                'real' => $get,
                'decoded' => array()
            );
            if(strpos($response['real'],'<html') || strpos($response['real'],'<body') || strpos($response['real'],'<head')){
                curl_close ($ch);
                return $response;
            }
            else if(strpos($get,'<') !== false && strpos($get,'>') !== false){
                $temp=explode('<',explode('>',$get)[0])[1];
                $json = json_encode(array($temp=>simplexml_load_string($get)));
                $response['decoded'] = json_decode($json, true);
            }
        }
        else if(curl_errno($ch) == 28){
            $response = array(
                'status' => 'timeout',
                'real' => '',
                'decoded' => array()
            );
        }
        curl_close ($ch);
        return $response;
    }
}

if(!function_exists('to_float')){
    function to_float(&$num = 0, $digit = 2){
        $num = number_format((float)$num, $digit, '.', '');
    }
}

if(!function_exists('only_int')){
    function only_int(&$num = ''){
        preg_match_all('!\d+!', $num, $matches);
        return implode('', $matches[0]);
    }
}

if(!function_exists('date_now')){
    function date_now($format = 'Y-m-d H:i:s', $micro = false){
        $CI  =& get_instance();
        if($micro !== false){
            $t = microtime(true);
            $micro = sprintf("%06d",($t - floor($t)) * 1000000);
            $d = json_decode(json_encode(new DateTime( date('Y-m-d H:i:s.'.$micro, $t))),true)['date'];
        }
        else{
            $d = date($format);
        }
        return $d;
    }
}

if(!function_exists('split_string')){
    function split_string($str, $length = 1, $char_count = 1, $char = ' '){
        $str = chunk_split($str,$char_count,$char);
        return substr($str,0,$length*($char_count+1)).str_replace(' ','',substr($str,$length*($char_count+1)));
    }
}

if(!function_exists('check_num')){
    function check_num($num = '', $dec = false, $digit = 2){
        if(is_array($num)){
            return false;
        }
        if(strpos($num, ' ') !== FALSE){
            $num = str_replace(' ','',$num);
        }
        if(strpos($num, '-') !== FALSE){
            $num = str_replace('-','',$num);
        }
        if($dec === true){
            $digit = ($digit+1)*-1;
            for($i=$digit;$i<=-2;$i++){
                if(substr($num,$i,1) == '.' && strpos(substr($num,0,$i), '.') === FALSE){
                    $num = str_replace('.','',$num);
                }
            }
        }
        if(ctype_digit((string)$num)) return true;
        else return false;
    }
}

if(!function_exists('check_date')){
    function check_date($date, $format = 'Y-m'){
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}

if(!function_exists('check_string')){
    function check_string($string = '', $space = false, $underscore = false){
        if(is_array($string)){
            return false;
        }
        if($space === true){
            if(strpos($string, ' ') !== FALSE){
                $string = str_replace(' ','',$string);
            }
        }
        if($underscore === true){
            if(strpos($string, '_') !== FALSE){
                $string = str_replace('_','',$string);
            }
        }
        if(ctype_alpha($string)) return true;
        else return false;
    }
}

if(!function_exists('check_string_num')){
    function check_string_num($string = '', $space = false, $underscore = false, $dec = false){
        if(is_array($string)){
            return false;
        }
        if($space === true){
            if(strpos($string, ' ') !== FALSE){
                $string = str_replace(' ','',$string);
            }
        }
        if($underscore === true){
            if(strpos($string, '_') !== FALSE){
                $string = str_replace('_','',$string);
            }
        }
        if($dec === true){
            if(strpos($string, '.') !== FALSE){
                $string = str_replace('.','',$string);
            }
        }
        if(ctype_alnum((string)$string)) return true;
        else return false;
    }
}

if(!function_exists('check_url')){
    function check_url($url = ''){
        if(is_array($url)){
            return false;
        }
        if(!strpos($url, 'http://')){
            return false;
        }
        return true;
    }
}

if(!function_exists('check_str_inarray')){
    function check_str_inarray($input, $search=array()) {
        $result = array();
        $count = count($search);
        for($i=0;$i<$count;$i++){
            if(strpos($input, $search[$i]) !== FALSE){
                return true;
            }
        }
        return false;
    }
}

if(!function_exists('to_date')){
    function to_date($input, $date_format = 'd M Y'){
        $CI  =& get_instance();
        $date_int = '';
        
        if(check_num($input)){
            //int
            if(strlen($input)!=6 && strlen($input)!=8){
                return $input;
            }
            else if(strlen($input) == 8){
                return date($date_format, strtotime($input));
            }
            else if(strlen($input) == 6){
                $input .= '01';
                return date($date_format, strtotime($input));
            }
            else{
                $d = substr($input,0,2);
                $m = substr($input,2,2);
                $y = substr($input,4);
                if(substr($input,2,2) > 12){
                    $y = substr($input,0,4);
                    $m = substr($input,4,2);
                    $d = '01';
                }
                if($y < 69){
                    $y += 2000;
                }
                $input = $y.$m.$d;
                return date($date_format, strtotime($input));
            }
        }
        else{
            $months = array("JAN" => "JAN", "FEB" => "FEB", "MAR" => "MAR", "APR" => "APR", "MEI" => "MAY", "MAY" => "MAY", "JUN" => "JUN", "JUL" => "JUL", "AGT" => "AUG", "AGU" => "AUG", "AUG" => "AUG", "SEP" => "SEP", "OKT" => "OKT", "NOV" => "NOV", "DES" => "DEC", "DEC" => "DEC");
            //string
            if(check_str_inarray(strtoupper($input), array_keys($months))){
                
                //valid
                $input = preg_replace('/[^A-Za-z0-9\-]/', ' ', $input);
                $return = date($date_format, strtotime($input));
                if(strtotime($input) === FALSE){
                    if(check_num(substr($input,0,2))){
                        $d = substr($input,0,2);
                        $m = $months[strtoupper(substr($input,3,3))];
                        $y = substr($input, -4);
                        $m = $d.' '.$m;
                    }
                    else{
                        $m = $months[strtoupper(substr($input,0,3))];
                        $y = substr($input, -4);
                    }
                    $input = $m.' '.$y;
                    $return = date($date_format, strtotime($input));
                }
                if(substr($return,0,2) == '01'){
                    if(strpos($input, '001') !== FALSE){
                        return substr($return,3);
                    }
                    else if(strpos($input, '01') !== FALSE){
                        return $return;
                    }
                    else{
                        return substr($return,3);
                    }
                }
                return $return;
            }
            else{
                if(strtotime($input) !== FALSE){
                    return date($date_format, strtotime($input));
                }
                return $input;
            }
        }
    }
}
?>