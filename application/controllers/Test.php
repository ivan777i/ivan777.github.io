<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Test extends API_Controller{

    public function clear_post(){
        if($this->env->state == 'development'){
            $bk = $this->load->database('backend', true);
            $db = $this->load->database('default', true);
            $db->query("TRUNCATE merchant_mutasi");
            $db->query("TRUNCATE merchant_projection");
            $bk->query("TRUNCATE master_globalsetting");
            $this->response(array("success" => true));
        }
    }

    public function insertglobal_post(){
        if($this->env->state == 'development'){
            $post = $this->post();
            $bk = $this->load->database('backend', true);
            $params = array(
                "globalset_detail" => $post["detail"],
                "globalset_isi" => $post["isi"]
            );
            $bk->insert("master_globalsetting", $params);
            $this->response(array("success" => ($bk->affected_rows() != 1) ? false : true));
        }
    }
}
?>