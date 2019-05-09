<?php

class Niveau_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function getAllLevels() {
        $query = $this->db->get('niveau');

        return $query->result_array();
    }

    public function getNiveau($x){
        $query = $this->db->get_where('niveau', array('niveau' => $x));

        return $query->row();
    }
}