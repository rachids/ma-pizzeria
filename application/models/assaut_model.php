<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zuco
 * Date: 11/07/14
 * Time: 21:00
 * To change this template use File | Settings | File Templates.
 */
class Assaut_model extends CI_Model
{
    public function __construct(){
        $this->load->database();
    }

    public function programmerAttaque($mission, $cible, $commanditaire) {
        $data = array(
            'restau_cible'              =>  $cible,
            'restau_commanditaire'      =>  $commanditaire,
            'ordre'                     =>  $mission,
            'etat'                      => 'en cours'
        );

        return $this->db->insert('vandalisme', $data);
    }

    public function get($idCible = null){
        if($idCible){
            $this->db->get_where('vandalisme','restau_cible = '.$idCible)->result_array;
        }

        $sql = 'SELECT * FROM vandalisme WHERE etat = "en cours"';

        return $this->db->query($sql)->result_array();
    }

    public function setMissionDone($id) {
        $sql = 'UPDATE vandalisme SET etat = "done" WHERE id = ?';

        return $this->db->query($sql, array($id));
    }

}
