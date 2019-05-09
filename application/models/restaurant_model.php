<?php

class Restaurant_model extends CI_Model {
    public function __construct() {
        $this->load->database();
    }

    /**
     * Ajouter un restaurant à la BDD
     */
    public function addRestaurant() {
        if(!$this->session->userdata('logged_in')) {
            return false;
        } else {
            $idJoueur = $this->session->userdata('id');

            /**
             * On vérifie que le joueur n'a pas déjà un restaurant !
             */
            if(!$this->hasRestaurant($idJoueur)) {

                $date = new DateTime();
                $date->modify('+ 2 days');

                $data = array(
                    'nom'       =>  $this->input->post('nom'),
                    'argent'    =>  $this->config->item('restaurant_argent'),
                    'capacite'  =>  $this->config->item('restaurant_capacite'),
                    'stock'     =>  $this->config->item('restaurant_stock'),
                    'notoriete' =>  $this->config->item('restaurant_notoriete'),
                    'etat'      =>  $this->config->item('restaurant_etat'),
                    'etatMax'   =>  $this->config->item('restaurant_etat'),
                    'open'      =>  $this->config->item('restaurant_openMax'),
                    'openMax'   =>  $this->config->item('restaurant_openMax'),
                    'police'   =>  $date->format('Y-m-d H:i:s'),
                    'id_joueur' =>  $idJoueur,
                );

                return $this->db->insert('restaurant', $data);
            }
        }

        return false;
    }

    /**
     * Ajouter des points de Batiments (etatMax)
     * @param int $add  Nombre de points à ajouter
     * @param int $cout Somme à déduire
     */
    public function addPDB($add,$cout) {
        $sql = 'UPDATE restaurant SET argent = argent - ?, etatMax = etatMax + ?
        WHERE id_joueur = ?';

        return $this->db->query($sql, array($cout, $add, $this->session->userdata('id')));
    }

    public function addStock($add,$cout) {
        $sql = 'UPDATE restaurant SET argent = argent - ?, stock = stock + ?
        WHERE id_joueur = ?';

        return $this->db->query($sql, array($cout, $add, $this->session->userdata('id')));
    }

    public function addCapacite($add,$cout) {
        $sql = 'UPDATE restaurant SET argent = argent - ?, capacite = capacite + ?
        WHERE id_joueur = ?';

        return $this->db->query($sql, array($cout, $add, $this->session->userdata('id')));
    }

    public function reparer($add,$cout) {
        $sql = 'UPDATE restaurant SET argent = argent - ?, etat = etat + ?
        WHERE id_joueur = ?';

        return $this->db->query($sql, array($cout, $add, $this->session->userdata('id')));
    }

    /**
     * Soustraire de l'argent à la caisse du restau
     * @param  int $montant Somme d'argent à virer
     * @return bool
     */
    public function payer($montant) {
        $sql = 'UPDATE restaurant SET argent = argent - ?
        WHERE id_joueur = ?';

        return $this->db->query($sql, array($montant, $this->session->userdata('id')));
    }

    public function debiter($montant, $id) {
        $sql = 'UPDATE restaurant SET argent = argent - ?
        WHERE id = ?';

        return $this->db->query($sql, array($montant, $id));
    }

    public function encaisser($montant, $id_restau) {
        $sql = 'UPDATE restaurant SET argent = argent + ?
        WHERE id = ?';

        return $this->db->query($sql, array($montant, $id_restau));
    }

    public function addXp($montant, $id_restau) {
        $sql = 'UPDATE restaurant SET experience = experience + ?
        WHERE id = ?';

        return $this->db->query($sql, array($montant, $id_restau));
    }

    public function levelUp($id_restau){
        $sql = 'UPDATE restaurant SET experience = 0, notoriete = notoriete + 1
        WHERE id = ?';

        return $this->db->query($sql, array($id_restau));        
    }

    public function ouverture($id_restau) {
        $sql = 'UPDATE restaurant SET open = open - 1
        WHERE id = ?';

        return $this->db->query($sql, array($id_restau));
    }

    /**
     * Vérifie si l'ID Joueur détient un restaurant
     * @param  int  $id ID du joueur
     * @return boolean
     */
    public function hasRestaurant($id = null) {
        $sql = 'SELECT COUNT(*) as nb FROM restaurant WHERE id_joueur = ?';

        $data = $this->db->query($sql, array($id))->result_array();

        return $data[0]['nb'];
    }

    /**
     * Récupère un restaurant
     * @param  int $id ID du restau
     * @return array
     */
    public function getRestaurant($id = null) {
        if($id) {
            $sql = 'SELECT * FROM restaurant WHERE id = ?';

            return $this->db->query($sql, array($id))->row();
        }

        $query = $this->db->get('restaurant');
        return $query->result_array();
    }

    /**
     * Récupère un restaurant selon le joueur
     * @param  int $id ID du joueur
     * @return array
     */
    public function getRestaurantByMember($id = null) {
        $sql = 'SELECT * FROM restaurant WHERE id_joueur = ?';

        return $this->db->query($sql, array($id))->row();
    }

    public function getAllRestaurants() {
        $sql = 'SELECT * FROM restaurant';

        return $this->db->query($sql)->result_array();
    }

    public function getTopRestaurants($howMuch, $filtre = "notoriete") {

        switch($filtre){
            case 'notoriete':
                $order = 'notoriete DESC, experience DESC';
                break;
            case 'affluence':
                $order = 'affluenceTotale DESC';
                break;
            case 'richesse':
                $order = 'argent DESC';
                break;
            case 'capacite':
                $order = 'capacite DESC';
                break;
            default:
                $order = 'notoriete DESC, experience DESC';
                break;
        }

        $sql = 'SELECT * FROM restaurant ORDER BY '.$order.' LIMIT 0, ?';

        return $this->db->query($sql, array($howMuch))->result_array();
    }

    function count(){
        $sql = 'SELECT COUNT(*) as nb FROM restaurant';

        return $this->db->query($sql)->row()->nb;
    }

    function update($id, $array) {
        $this->db->where('id', $id);
        return $this->db->update('restaurant', $array);
    }

    public function ajoutAffluence($id, $affluence){
        $sql= 'UPDATE restaurant SET affluenceDay = affluenceDay + ?, affluenceTotale = affluenceTotale + ?
        WHERE id = ?';

        return $this->db->query($sql, array($affluence, $affluence, $id));
    }

    public function affecterEtatBatiment($howMuch, $idRestau){
        $sql = 'UPDATE restaurant SET etat = GREATEST(etat - ?, 0)
        WHERE id = ?';

        return $this->db->query($sql, array($howMuch, $idRestau));
    }

    public function setProtectionPoliciere($date, $id){
        $sql = 'UPDATE restaurant SET police = ?
        WHERE id = ?';

        return $this->db->query($sql, array($date->format('Y-m-d H:i:s'), $id));
    }
}