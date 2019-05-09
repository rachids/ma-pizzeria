<?php

class Book_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function getMessages() {
        $query = $this->db->get('book');

        return $query->result_array();
    }

    public function addMessage() {

        $data = array(
            'pseudo'    =>  $this->input->post('pseudo'),
            'message'   =>  $this->input->post('message'),
            'date'      =>  date('Y-m-d H:i:s'),
        );

        return $this->db->insert('book', $data);
    }
}