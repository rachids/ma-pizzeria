<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zuco
 * Date: 26/07/14
 * Time: 00:02
 * To change this template use File | Settings | File Templates.
 */
class Param_model extends CI_Model {
    public function __construct() {
        $this->load->database();
    }

    public function checkEventEnCours($conf = "pizza"){
       return $this->db->get_where('param', array('param' => $conf))->row();
    }

    public function initializeEvent($conf = "pizza"){
        $data = array(
            'event'         =>  0,
            'prct_event'    =>  0
        );

        $this->db->where('param', $conf);
        $this->db->update('param', $data);
    }

    public function eventUpChance($montant){
        $this->db->where('param', 'pizza');
        $this->db->set('prct_event', 'prct_event+'.$montant, FALSE);
        $this->db->update('param');
    }

    public function setEvent($id, $conf = 'pizza'){
        $data = array(
            'event'         =>  $id,
            'prct_event'    =>  0
        );

        $this->db->where('param', $conf);
        $this->db->update('param', $data);
    }
}