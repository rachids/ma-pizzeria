<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zuco
 * Date: 09/07/14
 * Time: 13:19
 * To change this template use File | Settings | File Templates.
 */
class Employe_model extends CI_Model
{
    public function __construct() {
        $this->load->database();
    }

    public function addEmploye($aCaracteristiques) {
        $caracs = json_encode($aCaracteristiques);

        $data = array(
            'nom'              =>  $this->input->post('nom'),
            'description'      =>  $this->input->post('description'),
            'caracteristique'  =>  $caracs
        );

        return $this->db->insert('employes', $data);
    }


    public function get($idRestau) {
        $sql = 'SELECT e.*, r.niveau
        FROM `employes` e
        LEFT JOIN employes_restaurant r ON r.id_employe = e.id AND id_restau = ?
        ORDER BY e.nom';

        return $this->db->query($sql, array($idRestau))->result_array();
    }

    public function getRestauEmploye($idEmploye, $idRestau) {
        $sql = 'SELECT * FROM employes_restaurant WHERE id_employe = ? AND id_restau = ?';
        return $this->db->query($sql, array($idEmploye, $idRestau))->row();
    }

    public function recruter($idEmploye, $idRestaurant) {
        $sql = 'INSERT INTO `employes_restaurant` (`id_employe`, `id_restau`, `niveau`, `lastAction`)
        VALUES (?, ?, ?, CURRENT_TIMESTAMP)
        ON DUPLICATE KEY UPDATE niveau = niveau + 1, lastAction = CURRENT_TIMESTAMP';

        return $this->db->query($sql, array($idEmploye, $idRestaurant, 1));
    }

    public function licencier($idEmploye, $idRestaurant) {
        $sql = 'DELETE FROM employes_restaurant WHERE id_employe = ? AND id_restau = ?';

        return $this->db->query($sql, array($idEmploye, $idRestaurant));
    }

    public function getEmployeByID($id) {
        $sql = 'SELECT * FROM employes WHERE id = ?';

        return $this->db->query($sql, array($id))->row();
    }
}
