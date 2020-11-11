<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testing {
    private $CI;
    public function __construct(){
        $this->CI =& get_instance();
    }

    public function index($url = '', $par = ''){
        if(!empty($this->CI->env->test[$url][$par])){
            $response = array(
                'status' => $this->CI->env->test[$url][$par]['status'],
                'real' => json_encode($this->CI->env->test[$url][$par]['response']),
                'decoded' => $this->CI->env->test[$url][$par]['response']
            );
            return $response;
        }
        else{
            false_response('config', 'Testing Not Available: '.$url.' | par: '.$par);
        }
    }

    public function callback($url, $par, $trx_id){
        if(isset($this->CI->env->test[$url][$par]['callback'])){
            if(!empty($this->CI->env->test[$url][$par]['callback'])){
                // sleep(5);
                create_file(JKFILE.'call_'.$trx_id.'.json', json_encode(array("request" => $this->CI->env->test[$url][$par]['callback'], "time" => "100000")));
            }
            else{
                //do nothing
            }
        }
        else{
            false_response('config', 'Testing Not Available: '.$url.' | par: '.$par);
        }
    }
}