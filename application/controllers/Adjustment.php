<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adjustment extends API_Controller{

    public function index_post(){
        /*
        Version 1.0
        Date: 11 November 2020
        Features:
        - Update Stocks quantity

        Helpers:
        - module_helper (autoload)

        Models:
        - stockdb (autoload)
        */

        //validasi request
        $post = getdata();
        $this->post_validation($post);
        
        //update stocks
        $result = array();
        $adjusted = 0;
        foreach($post as $val){
            $res_db = array(
                "status" => "Failed",
                "error_message" => "Invalid Product",
                "updated_at" => '',
                "location_id" => ''
            );
            //get stock requested
            $stock = $this->stockdb->get_stock_id_name($val['location_id'], $val['product']);
            if(empty($stock['id']) || (isset($stock['id']) && !check_num($stock['id']))){
                $res_db['location_id'] = $val['location_id'];
                $res_db['updated_at'] = date('Y-m-d H:i:s');
                array_push($result, $res_db);
                continue;
            }

            //validasi jika stock tidak mencukupi
            if($stock['qty'] < abs($val['adjustment'])){
                $res_db['location_id'] = $val['location_id'];
                $res_db['error_message'] = 'Not Enough Quantity';
                $res_db['updated_at'] = date('Y-m-d H:i:s');
                array_push($result, $res_db);
                continue;
            }

            //hitung stock
            $qty = $stock['qty'] + $val['adjustment'];

            //update stock
            $this->db->trans_start();
            $this->stockdb->update_stock($val['location_id'], $qty);
            if($this->db->trans_status() === false){
                $this->db->trans_rollback();
                $res_db['location_id'] = $val['location_id'];
                $res_db['updated_at'] = date('Y-m-d H:i:s');
                $res_db['error_message'] = 'Update Failed';
                array_push($result, $res_db);
                continue;
            }

            //insert log
            $desc = array(
                "type" => ($val['adjustment'] < 0) ? 2 : 1,
                "adjustment" => $val['adjustment'],
                "qty" => $qty
            );
            if(!$this->stockdb->insert_log($val['location_id'], $desc)){
                $this->db->trans_rollback();
                $res_db['location_id'] = $val['location_id'];
                $res_db['updated_at'] = date('Y-m-d H:i:s');
                $res_db['error_message'] = 'Update Failed';
                array_push($result, $res_db);
                continue;
            }
            $this->db->trans_complete();

            //success
            $res_db['status'] = "Success";
            unset($res_db['error_message']);
            $res_db['location_id'] = $val['location_id'];
            $res_db['updated_at'] = date('Y-m-d H:i:s');
            $res_db['location_name'] = $stock['loc'];
            $res_db['product'] = $val['product'];
            $res_db['adjustment'] = (int)$val['adjustment'];
            $res_db['stock_quantity'] = $qty;
            array_push($result, $res_db);
            $adjusted++;
            continue;
        }
        $respon = array(
            "status_code" => 200,
            "requests" => count($post),
            "adjusted" => $adjusted,
            "results" => $result
        );
        respon($respon);
    }

    private function post_validation(&$post){
        $error = array("status_code" => 400); //request tidak sesuai
        if(!is_array($post) || empty($post)){
            respon($error);
        }
        else{
            foreach($post as $val){
                if(!isset($val['location_id'])){
                    respon($error);
                }
                else if(!check_num($val['location_id'])){
                    respon($error);
                }
                if(empty($val['product'])){
                    respon($error);
                }
                if(empty($val['adjustment'])){
                    respon($error);
                }
                else if(!check_num($val['adjustment'])){
                    respon($error);
                }
            }
        }
    }
}