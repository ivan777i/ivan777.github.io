<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stockdb extends CI_Model {

    function __construct(){
        parent::__construct();        
        $this->db->conn_id->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, true);
    }

    function update_stock($id, $qty){
        $params = array(
            "qty" => $qty
        );
        $this->db->update('master_stock',$params);
    }

    function insert_log($id, $desc){
        $params = array(
            "log_date" => date('Y-m-d H:i:s'),
            "stock_id" => $id,
            "log_desc" => json_encode($desc)
        );
        $this->db->insert('rel_stock_log',$params);
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    function get_all(){
        $this->db->select("id, loc, qty, product");
        $this->db->from("master_stock");
        $this->db->order_by("id ASC");
        $query = $this->db->get();
        if($query){
            $result = $query->result_array();
            $query->free_result();
            return $result;
        }
        else{
            return false;
        }
    }

    function get_stock_id_name($id, $name){
        $this->db->select("id, loc, qty, product");
        $this->db->from("master_stock");
        $this->db->where("id = '$id' AND product = '$name'");
        $this->db->limit(1);
        $query = $this->db->get();
        if($query){
            $result = $query->row_array();
            $query->free_result();
            return $result;
        }
        else{
            return false;
        }
    }

    function get_stock_id($id){
        $this->db->select("id, loc, qty, product");
        $this->db->from("master_stock");
        $this->db->where("id = '$id'");
        $this->db->limit(1);
        $query = $this->db->get();
        if($query){
            $result = $query->row_array();
            $query->free_result();
            return $result;
        }
        else{
            return false;
        }
    }

    function get_stock_log($id){
        $this->db->select("log_id as id, ( CASE WHEN JSON_UNQUOTE(JSON_EXTRACT(log_desc, '$.type')) = 1 THEN 'Inbound' WHEN JSON_UNQUOTE(JSON_EXTRACT(log_desc, '$.type')) = 2 THEN 'Outbound' ELSE 'Unknown' END) AS type, log_date AS created_at, CAST(JSON_UNQUOTE(JSON_EXTRACT(log_desc, '$.adjustment')) AS INT) AS adjustment, CAST(JSON_UNQUOTE(JSON_EXTRACT(log_desc, '$.qty')) AS INT) AS quantity");
        $this->db->from("rel_stock_log");
        $this->db->where("stock_id = '$id'");
        $this->db->order_by("log_id DESC");
        $query = $this->db->get();
        if($query){
            $result = $query->result_array();
            $query->free_result();
            return $result;
        }
        else{
            return false;
        }
    }
}