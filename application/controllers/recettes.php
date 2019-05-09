<?php

class Recettes extends CI_Controller {
    private $idRestau;

    public function __construct() {
        parent::__construct();
        $this->load->model('restaurant_model');
        $this->load->model('ingredient_model');
        $this->load->model('pizza_model');

        if(!$this->session->userdata('logged_in')){
            $this->session->set_flashdata('flashmessage', 'Il faut être connecté pour voir cette page.');
            redirect('user/connexion','location');
        } elseif(!$this->restaurant_model->hasRestaurant($this->session->userdata('id'))) {
            redirect('jeu/restaurant','location');
        }

        $restaurant = $this->restaurant_model->getRestaurantByMember($this->session->userdata('id'));

        $this->idRestau = $restaurant->id;
    }

    public function index(){
        redirect('jeu/recettes/gestion','location');
    }

    public function gestion(){
        $header['css'] = '<link href="'.base_url('assets/css/tableau.css').'" rel="stylesheet">';
        $footer['javascript'] = '<script type="text/javascript" src="'.base_url('assets/js/tableau.js').'"></script>';

        $data['pizzas'] = $this->cleanRecette($this->pizza_model->getPizzaByRestaurant($this->idRestau));

        $this->load->view('templates/header', $header);
        $this->load->view('jeu/recettes/gestion', $data);
        $this->load->view('templates/footer', $footer);
    }

    public function creer(){
        if($this->pizza_model->countPizzasByRestaurant($this->session->userdata('idRestaurant'))->nb >= 3) {
            $this->session->set_flashdata('flashmessage', 'Vous ne pouvez pas créer de nouvelles recettes.');
            redirect('jeu/recettes/gestion','location');            
        }

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nom', 'Nom de votre Pizza', 'required|alpha_dash_space|min_length[5]|max_length[31]');
        $this->form_validation->set_rules('prix', 'prix', 'required|integer|greater_than[0]|less_than[31]');
        $this->form_validation->set_rules('ingredients', 'Ingrédients', 'required|callback_checkRecette');

        if($this->form_validation->run()) {
            $this->pizza_model->addPizza($this->idRestau);
            $this->session->set_flashdata('flashmessage', 'Votre recette est créée !');
            redirect('jeu/recettes', 'location');
        }

        $this->load->model('ingredient_model');
        $data['ingredients'] = $this->ingredient_model->getAll();
     
        $this->load->view('templates/header');
        $this->load->view('jeu/recettes/create', $data);
        $this->load->view('templates/footer');
    }

    public function supprimer($id) {
        if($this->isMyPizza($id)) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('idPizza', 'Erreur', 'required');
             $this->form_validation->set_message('checkRecette', 'Il est interdit de modifier ce formulaire ! Ceci est considéré comme de la triche.
                Les abus seront punis d\'une suppression de compte sans préavis!');

            if($this->form_validation->run()) {

                $id = $this->input->post('idPizza');

                if($this->isMyPizza($id)) {
                    $this->pizza_model->delete($id);
                    $this->session->set_flashdata('flashmessage', 'Votre recette a été supprimée du menu !');
                    redirect('jeu/recettes', 'location');
                } else {
                    $this->session->set_flashdata('flashmessage', 'Action interdite.');
                    redirect('jeu/recettes','location');
                }
            }

            $data['idPizza'] = $id;
            $data['pizza'] = $this->pizza_model->get($id);
            
            $this->load->view('templates/header');
            $this->load->view('jeu/recettes/supprimer', $data);
            $this->load->view('templates/footer');
        } else {
            $this->session->set_flashdata('flashmessage', 'Action interdite.');
            redirect('jeu/recettes','location');
        }
    }

    public function modifier($id = 0){
        $pizza = $this->isMyPizza($id);

        #Si la pizza est la nôtre :
        if(is_object($pizza)){
            $this->load->helper('form');
            $this->load->library('form_validation');

            $this->form_validation->set_rules('nom', 'Nom de votre Pizza', 'required|alpha_dash_space|min_length[5]|max_length[31]');
            $this->form_validation->set_rules('prix', 'prix', 'required|integer|greater_than[0]|less_than[31]');
            $this->form_validation->set_rules('ingredients', 'Ingrédients', 'required|callback_checkRecette');

            if($this->form_validation->run()) {

                $ingredients = json_encode($this->input->post('ingredients'));

                $data = array(
                    'nom'           =>  $this->input->post('nom'),
                    'ingredients'   =>  $ingredients,
                    'prix'          =>  $this->input->post('prix'),
                );

                $this->pizza_model->updatePizza($data, $id);
                $this->session->set_flashdata('flashmessage', 'Votre recette a été modifiée !');
                redirect('jeu/recettes', 'location');
            }

            $data['pizza'] = $pizza;

            $this->load->model('ingredient_model');
            $data['ingredients'] = $this->ingredient_model->getAll();

            $this->load->view('templates/header');
            $this->load->view('jeu/recettes/edit', $data);
            $this->load->view('templates/footer');
        } else {
            $this->session->set_flashdata('flashmessage', 'Recette introuvable.');
            redirect('jeu/recettes/gestion','location');
        }
    }

    public function checkRecette() {
        $nbChecked = count($this->input->post('ingredients'));

        if($nbChecked > 5) {
            $this->form_validation->set_message('checkRecette', 'Vous ne pouvez cocher que 
                <strong>'.$this->config->item('pizza_ingredientsMax').' %s</strong> (Vous en avez coché '.$nbChecked.')');
            return FALSE;
        } elseif($nbChecked < 1) {
            $this->form_validation->set_message('checkRecette', 'Vous devez cocher au moins 
                <strong>1 ingrédient</strong>.');
            return FALSE;
        } else {
            #On vérifie que chaque ingrédient existe dans notre DB
            foreach($this->input->post('ingredients') as $ingredient) {
                if(!$this->ingredient_model->get($ingredient)) {
                    $this->form_validation->set_message('checkRecette', 'Erreur lors du choix des ingrédients (Veuillez recharger la page et recommencer)');
                    return FALSE;
                }
            }

            return true;
        }
    }

    private function isMyPizza($id) {
        return $this->pizza_model->getPizzaByIdAndRestaurant($id, $this->idRestau);
    }

    private function cleanRecette($arrayPizzas) {
        $mesPizzasClean = array();
        foreach($arrayPizzas as $pizza) {
            $mesPizzasClean[] = array(
                'id' => $pizza['id'],
                'nom'=> $pizza['nom'],
                'note'=> $pizza['note'],
                'prix'=> $pizza['prix'],
                'ingredients'=> $this->fetchJsonIngredients($pizza['ingredients'])
            );
        }

        return $mesPizzasClean;
    }

    private function fetchJsonIngredients($sJSON) {
        $string = json_decode($sJSON);
        $listeIng = '';
        foreach($string as $ingredient) {
            $listeIng .= $this->ingredient_model->get($ingredient)->nom . ', ';
        }
        return trim($listeIng, ", ");
    }
}