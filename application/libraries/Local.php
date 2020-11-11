<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Local {
    private $CI;
    public function __construct(){
        //untuk mengambil resources dari controller yang memanggil
        $this->CI =& get_instance();
    }
    
    public function get_balance($merchant_id){
        /*
        Date: 5 Juni 2020
        Features:
        - untuk get balance

        Models:
        - saldodb (autoload)
        */

        //get last projection from db
        $projection = $this->CI->saldodb->get_projection($merchant_id);
        //set default parameters
        $mutasi_id = 0;
        $projection_balance = 0;
        $mutasi_sum = 0;

        //isi mutasi_id dengan result db jika ada
        if(!empty($projection['mutasi_id'])){
            $mutasi_id = $projection['mutasi_id'];
        }

        //isi projection_balance dengan result db jika ada
        if(!empty($projection['projection_balance'])){
            $projection_balance = $projection['projection_balance'];
        }

        //get sum from db IN merchant_id
        $sum = $this->CI->saldodb->get_sum($merchant_id, $mutasi_id);
        //isi total dengan result db jika ada
        if(!empty($sum['total'])){
            $mutasi_sum = $sum['total'];
        }

        //return total = jumlah mutasi + jumlah di projection
        return $mutasi_sum + $projection_balance;
    }
}