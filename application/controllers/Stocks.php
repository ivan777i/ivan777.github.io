<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stocks extends API_Controller{

    public function index_get(){
        /*
        Version 1.0
        Date: 11 November 2020
        Features:
        - Get All Stocks

        Helpers:
        - module_helper (autoload)

        Models:
        - stockdb (autoload)
        */
        
        //get all stocks
        $stocks = $this->stockdb->get_all();
        if($stocks === false && is_bool($stocks)){
            $status = 500;
            $message = "Failed";
            $stocks = array();
        }
        else{
            $status = 200;
            $message = "Success";
        }
        $respon = array(
            "status_code" => $status,
            "status_message" => $message,
            "stocks" => $stocks
        );
        respon($respon);
    }
}