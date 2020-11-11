<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!defined('AGENT')) define('AGENT',"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36");

if (!function_exists('curl_def')) {
    function &curl_def($co='') {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, AGENT);
        if($co!='') {
            curl_setopt ($ch, CURLOPT_COOKIEFILE, $co);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $co); 
        }
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        return $ch;
    }
}

if (!function_exists('geturl')) {
    function geturl($url,$co='') {
        $ch = curl_def($co);
        curl_setopt($ch, CURLOPT_URL, $url);
        $get = curl_exec ($ch);	
        curl_close ($ch);
        return $get;
    }
}

if (!function_exists('posturl')) {
    function posturl($url,$data,$co='') {
        $ch = curl_def($co);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_POST, true);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $data);
        $get = curl_exec ($ch);	
        curl_close ($ch);
        return $get;
    }
}

if (!function_exists('jsonurl')) {
    function jsonurl($url,$data,$co='') {
        $ch = curl_def($co);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt ($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        $get = curl_exec ($ch);	
        curl_close ($ch);
        return $get;
    }
}

if (!function_exists('formurl')) {
    function formurl($url,$data,$method='GET',$co='') {
        $ch = curl_def($co);
        $burl='';
        if($method=='GET') foreach($data as $key => $val) $burl.='/'.$key.'/'.$val;
        else if($method=='DELETE') foreach($data as $val) $burl.='/'.$val;
        curl_setopt($ch, CURLOPT_URL, $url.$burl);
        if($method=='GET' || $method=='DELETE')
        {
          curl_setopt ($ch, CURLOPT_POSTFIELDS, '');
          curl_setopt ($ch, CURLOPT_POST, false);
        }
        else
        {
          if($method=='POST') curl_setopt ($ch, CURLOPT_POST, true);
          else {curl_setopt ($ch, CURLOPT_POST, false);curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, $method);}
          curl_setopt ($ch, CURLOPT_POSTFIELDS, $data);
        }
        $get = curl_exec ($ch);	
        $info = curl_getinfo($ch);
        curl_close ($ch);
        return array('info'=>$info,'get'=>$get);
    }
}

if (!function_exists('get_real_ip')) {
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

if (!function_exists('convert_ascii')) {
    function convert_ascii($return) {
        $jum=0;
        for($i=0,$n=strlen($return);$i<$n;$i++) {
            $temp=ord(substr($return,$i,1));
            $jum+=($temp>0)?$temp:0;
        }
        return $jum;
    }
}
?>