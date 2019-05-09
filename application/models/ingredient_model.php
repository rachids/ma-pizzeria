<?php

class Ingredient_model extends CI_Model {
    public function __construct() {
        $this->load->database();
    }

    public function retraitStock($montant, $id) {
        $sql = 'UPDATE ingredients SET stockDispo = stockDispo - ?
        WHERE id = ?';

        return $this->db->query($sql, array($montant, $id, $this->session->userdata('id')));
    }

    public function getAll() {
        $sql = 'SELECT * FROM ingredients ORDER BY nom';

        return $this->db->query($sql)->result_array();
    }

    public function get($id) {
        $sql = 'SELECT * FROM ingredients WHERE id = ?';

        return $this->db->query($sql, array($id))->row();
    }

    public function update($array, $id){
        $this->db->where('id', $id);
        $this->db->update('ingredients', $array);
    }
}