<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logs extends API_Controller{

    public function index_get($id){
        /*
        Version 1.0
        Date: 11 November 2020
        Features:
        - Get Logs

        Helpers:
        - module_helper (autoload)

        Models:
        - stockdb (autoload)
        */

        //get stock data
        $respon = array(
            "status_code" => 200,
            "status_message" => "Success, logs found",
            "location_id" => $id
        );

        $stock = $this->stockdb->get_stock_id($id);
        if(empty($stock['id']) || (isset($stock['id']) && !check_num($stock['id']))){
            $respon['status_code'] = 400; //id tidak ditemukan
            $respon['status_message'] = "Failed";
            respon($respon);
        }
        $respon['location_name'] = $stock['loc'];
        $respon['product'] = $stock['product'];
        $respon['current_qty'] = $stock['qty'];

        //get stock logs
        $logs = $this->stockdb->get_stock_log($id);
        if($logs === false && is_bool($logs)){
            $respon['status_code'] = 200; //id ditemukan, namun belum ada log
            $respon['status_message'] = "Failed";
            respon($respon);
        }
        $respon['logs'] = $logs;
        respon($respon);
    }
}