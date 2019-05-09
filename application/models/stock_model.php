<?php
class Stock_model extends CI_Model {
    public function __construct() {
        $this->load->database();
    }

    /**
     * Retourne le nombre de places disponible
     * @param  int $id       ID du restaurant
     * @param  int $stockMax Stock maximum du restaurant
     * @return int           Places disponibles dans le stock
     */
    public function stockLibre($id, $stockMax) {
        $sql = 'SELECT SUM(quantite) as nb FROM stock 
        WHERE id_restau = ?';

        $data = $this->db->query($sql, array($id))->result_array();
        return $stockMax - $data[0]['nb']; // Affiche l'espace libre

    }

    public function stockOccupe($id) {
        #COALESCE() retourne 0 si SUM = null
        $sql = 'SELECT COALESCE(SUM(quantite),0) as nb FROM stock 
        WHERE id_restau = ?';

        $data = $this->db->query($sql, array($id))->result_array();
        return $data[0]['nb']; // Affiche l'espace libre
    }

    public function ajoutStock($quantite, $id_restau, $id_ingredient) {

        $sql = 'INSERT INTO stock(id_restau, id_ingredient, quantite) 
        VALUES(?,?,?) ON DUPLICATE KEY UPDATE quantite = quantite + ?';

        $data = array($id_restau,$id_ingredient,$quantite,$quantite);

        return $this->db->query($sql, $data);
    }

    public function enStock($idIngredient, $idRestau) {
        $sql = 'SELECT COALESCE(SUM(quantite),0) as qte FROM stock WHERE id_ingredient = ?
        AND id_restau = ?';

        return $this->db->query($sql, array($idIngredient, $idRestau))->row();
    }

    public function retrait($aIngredients, $id_restau) {
        $sql = 'UPDATE stock SET quantite = quantite - 1
        WHERE id_ingredient IN ('.implode(',',$aIngredients).')
        AND id_restau = ?';

        return $this->db->query($sql, array($id_restau));
    }

    public function listing($id_restau) {
        $sql = 'SELECT i.id, i.nom, i.image, s.quantite as quantite FROM `stock` s, ingredients i
                WHERE i.id = s.id_ingredient AND s.id_restau = ?';

        return $this->db->query($sql, array($id_restau))->result_array();
    }

    public function retireVide($id_restau) {
        $sql = 'DELETE FROM `stock` WHERE `id_restau` = ? AND `quantite` = 0';

        return $this->db->query($sql, $id_restau);
    }

}