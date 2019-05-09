<?php

class Jeu extends CI_Controller {
    public function index(){
        if(!$this->session->userdata('logged_in')){
            $this->session->set_flashdata('flashmessage', 'Il faut être connecté pour voir cette page.');
            redirect('user/connexion','location');
        } else {
            #Si on a pas de restaurant
            if(!$this->restaurant_model->hasRestaurant($this->session->userdata('id'))) {
                #renvoi sur la page de création du restau
                $this->session->set_flashdata('flashmessage', 'Vous devez créer votre restaurant pour pouvoir jouer.');
                redirect('jeu/restaurant/creer','location');
            } else {
                #renvoi sur la page de gestion du restau
                redirect('jeu/restaurant/gestion','location');
            }
        }
    }
}