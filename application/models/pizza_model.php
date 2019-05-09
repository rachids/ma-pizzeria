<?php

class Pizza_model extends CI_Model {
    public function __construct() {
        $this->load->database();
    }

    public function addPizza($id) {
        $ingredients = json_encode($this->input->post('ingredients'));

        $data = array(
            'nom'           =>  $this->input->post('nom'),
            'ingredients'   =>  $ingredients,
            'prix'          =>  $this->input->post('prix'),
            'id_restau'     =>  $id,
        );

        return $this->db->insert('pizza', $data);
        
    }

    function updatePizza($array, $id) {
        $this->db->where('id', $id);
        return $this->db->update('pizza', $array);
    }

    public function saveMoyenne($notemoyenne, $idPizza){
        $sql = 'UPDATE pizza SET note = ? WHERE id = ?';

        return $this->db->query($sql, array($notemoyenne, $idPizza));
    }

    public function getPizzaByRestaurant($id) {
        $sql = 'SELECT * FROM pizza WHERE id_restau = ?';
        return $this->db->query($sql, array($id))->result_array();
    }

    public function countPizzasByRestaurant($id){
        $sql = 'SELECT COUNT(*) as nb FROM pizza WHERE id_restau = ?';
        return $this->db->query($sql, array($id))->row();
    }

    public function getPizzaByIdAndRestaurant($idpizza, $idrestau) {
        $sql = 'SELECT * FROM pizza WHERE id = ? AND id_restau = ?';
        return $this->db->query($sql, array($idpizza, $idrestau))->row();
    }

    public function get($id) {
        $sql = 'SELECT * FROM pizza WHERE id = ?';
        return $this->db->query($sql, array($id))->row();
    }

    public function delete($id) {
        $sql = 'DELETE FROM pizza WHERE id = ?';
        return $this->db->query($sql, array($id));
    }

    public function countPizzas($id) {
        $sql = 'SELECT COUNT(*) as nb FROM pizza
        WHERE id_restau = ?';

        $data = $this->db->query($sql, array($id))->row();

        return $data['nb'];
    }
}