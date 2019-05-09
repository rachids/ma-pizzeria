<?php

class Cron_model extends CI_Model
{
    public function __construct(){
        $this->load->database();
        $this->load->library('pizzalib');
    }

    public function restauUpdate(){
        $bestQL = 'SELECT id, nom, affluenceDay FROM restaurant WHERE notoriete = 1 AND affluenceDay > 0
        ORDER BY affluenceDay DESC LIMIT 0,1';
        $best = $this->db->query($bestQL)->row();

        #Seulement si on a un restau lvl 1 ayant eu au moins 1 client today
        if(!is_array($best)){
            $xpBoost = mt_rand(1,10);

            $messageTop = '<p class="text-primary"><strong>Le Jeune Restaurant le plus populaire du jour</strong><br/>
        Le restaurant <em>'.$best->nom.'</em> a eu la meilleure affluence avec <strong>'.$best->affluenceDay.' clients</strong>.
        Il remporte <strong>'.$xpBoost.'</strong> XP !</p>';

            $msgprive = '[MISE A JOUR] Votre restaurant a eu la meilleure affluence hier ('.$best->affluenceDay.' clients).
        Vous remportez <strong>'.$xpBoost.'</strong> XP.';
            $this->pizzalib->publierJournal($msgprive, 'prive', $best->id);


            $sqlXP = 'UPDATE restaurant SET experience = experience + '.$xpBoost.' WHERE id = '.$best->id;
            $this->db->query($sqlXP);

            $this->pizzalib->publierJournal($messageTop);
        }

        $sql = 'UPDATE restaurant SET open = openMax, affluenceDay = 0';

        $this->db->query($sql);

        $msg = '<p class="text-primary">Tous les restaurants peuvent de nouveau accueillir des clients.</p>';
        $this->pizzalib->publierJournal($msg);
    }

    public function gererSalaire() {
        $sql = 'SELECT id_restau FROM employes_restaurant GROUP BY id_restau';
        $aRestaurants = $this->db->query($sql)->result_array();

        foreach($aRestaurants as $idRestaurant) {
            $sqlSalaires = 'SELECT SUM(r.niveau * e.salaire) as salaire
            FROM `employes_restaurant` r, employes e
            WHERE r.id_employe = e.id AND r.id_restau = ?
            GROUP BY r.id_restau';

            $oFacture = $this->db->query($sqlSalaires, array($idRestaurant['id_restau']))->row();
            $restauActuel = $this->db->get_where('restaurant', array('id' => $idRestaurant['id_restau']))->row();
            $salaires = $oFacture->salaire;

            $this->restaurant_model->debiter($salaires, $idRestaurant['id_restau']);

            if($restauActuel->argent < $salaires){
                #Renvoi des employés !
                $this->db->where('id_restau', $idRestaurant['id_restau']);
                $this->db->delete('employes_restaurant');

                $message = '[MISE A JOUR] <strong>Déficit !</strong><br/>
                Vous avez versé <strong>'.$salaires.' '.pizzaMoney().'</strong> en salaire à vos employés. Votre caisse
                tombe en négatif ! Vous ne pouvez plus ouvrir votre restaurant tant que la caisse ne remonte pas au dessus
                de 0.<br/>
                Tous vos employés ont été licenciés.';
            }else {
                $message = '[MISE A JOUR] Vous avez versé <strong>'.$salaires.' '.pizzaMoney().'</strong> en salaire à vos employés.';
            }


            $this->pizzalib->publierJournal($message, 'prive', $idRestaurant['id_restau']);
        }

    }

    public function cleanReport(){
        $date = new DateTime();
        $date->modify( '-3 days' );

        $sql = 'DELETE FROM rapport WHERE visibilite = "prive" AND date <= ?';

        return $this->db->query($sql, array(strtotime($date->format('Y-m-d H:i:s'))));
    }
}
