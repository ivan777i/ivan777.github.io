<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Update_trx extends API_Controller{
    /*
    
    */

    public function v10_post(){
        /*
        Version 1.0
        Date: 
        Features:
        - 

        Libraries:
        - Local (autoload)

        Helpers:
        - module_helper (autoload)

        Models:
        - trxdb (autoload)
        */
        $post = $this->post();
        $this->v10_validation($post);
        $data = $post['data'];
        unset($post);

        $this->db->trans_start();

        //insert detail
        if(!empty($data['detail'])){
            $detail_keys = array_keys($data['detail']);
            foreach($detail_keys as $val){
                $detail = $this->trxdb->insert_detail($data['cart_id'], $data['trx_id'], $val, $data['detail'][$val]);
                if($detail === false && is_bool($detail)){
                    kibana_log('trx_user-error detail', array('cart_id' => $data['cart_id'], 'trx_id' => $data['trx_id'], 'key' => $val, 'value' => $data['detail'][$val], 'note' => "insert failed"));
                    false_response('message', 'insert detail failed', 'error.failed');
                }
            }
        }

        if(!empty($data['status']) && !empty($data['substatus'])){
            //insert log
            $params = array(
                "cart_id" => $data['cart_id'],
                "trx_id" => $data['trx_id'],
                "trxlog_tgl" => date_now('Y-m-d H:i:s'),
                "trxlog_isi" => json_encode(array()),
                "trxlog_status" => $data['status'],
                "trxlog_substatus" => $data['substatus']
            );
            $log = $this->trxdb->insert_log($params);
            if($log === false && is_bool($log)){
                kibana_log('trx_user-error log', array('params' => $params, 'note' => "insert failed"));
                false_response('message', 'insert log failed', 'error.failed');
            }
        }
        $this->db->trans_complete();

        succ_response();
    }

    private function v10_validation(&$post){
        /*
        Version 1.0
        Date: 
        Features:
        - Validasi request POST api versi 1.0
        */
        if(!empty($post['data'])){
            if(!isset($post['data']['cart_id'])){
                false_response('invalid', 'cart_id required', 'required', 'cart_id');
            }
            else if(!check_num($post['data']['cart_id'])){
                false_response('invalid', 'cart_id is not integer', 'type', 'cart_id');
            }
            if(empty($post['data']['trx_id'])){
                false_response('invalid', 'trx_id required', 'required', 'trx_id');
            }
            else if(!check_num($post['data']['trx_id'])){
                false_response('invalid', 'trx_id is not integer', 'type', 'trx_id');
            }
            if(empty($post['data']['detail']) && (empty($post['data']['status']) || empty($post['data']['substatus']))){
                false_response('invalid', 'status required', 'required', 'status');
            }
            else if(!empty($post['data']['detail']) && !is_array($post['data']['detail'])){
                false_response('invalid', 'detail is not array', 'type', 'detail');
            }
            else if(!empty($post['data']['status']) && !check_num($post['data']['status'])){
                false_response('invalid', 'status is not array', 'type', 'status');
            }
            else if(!empty($post['data']['substatus']) && !check_num($post['data']['substatus'])){
                false_response('invalid', 'substatus is not array', 'type', 'substatus');
            }
        }
        else{
            false_response('invalid', 'data required', 'required', 'data');
        }
    }
}