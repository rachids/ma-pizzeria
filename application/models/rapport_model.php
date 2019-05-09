<?php

class Rapport_model extends CI_Model{
    public function __construct() {
        $this->load->database();
    }

    public function addMessage($message, $visibilite, $id_restau) {
        $date = new DateTime();
        $data = array(
            'message'       =>  $message,
            'visibilite'    =>  $visibilite,
            'date'          =>  $date->getTimestamp(),
            'id_restau'     =>  $id_restau,
        );

        return $this->db->insert('rapport', $data);
    }   
    
    public function getMessagesByPlayer($id_restau, $limit) {
        $sql = 'SELECT * FROM rapport WHERE id_restau = ? ORDER BY date DESC LIMIT ?,?';

        return $this->db->query($sql, array($id_restau, (int) $limit, $this->config->item('pagination_limite')))->result_array();
    }

    public function getAllPublicMessages($limit) {
        $sql = 'SELECT * FROM rapport WHERE visibilite = "public" ORDER BY date DESC LIMIT ?,?';

        return $this->db->query($sql, array((int) $limit, $this->config->item('pagination_limite')))->result_array();
    } 

    public function viderMsg() {
        $sql = 'DELETE FROM rapport WHERE id_restau = ?';

        return $this->db->query($sql, array($this->session->userdata('idRestaurant')));
    }

    public function countAllMessages($id = 0){

        $sql = ($id === 0) ? 'SELECT COUNT(*) as nb FROM rapport WHERE id_restau = ?' : 'SELECT COUNT(*) as nb FROM rapport WHERE id_restau = ?';

        return $this->db->query($sql, array($id))->row();
    }
}