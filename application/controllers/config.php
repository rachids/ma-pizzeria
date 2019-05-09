<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zuco
 * Date: 04/07/14
 * Time: 18:04
 * To change this template use File | Settings | File Templates.
 */
class Config extends CI_Controller
{
    public function __construct() {
        parent::__construct();

        $this->load->library('pizzalib');
        #Si on est pas connecté: renvoi sur le formulaire de connexion
        if(!$this->session->userdata('logged_in')){
            show_404();
        } else {
            #Si on n'est pas Admin
            if(!$this->pizzalib->isAdmin($this->session->userdata('id'))) {
                show_404();
            }
        }
    }

    public function index() {
        $this->load->model('user_model');
        $this->load->model('restaurant_model');

        $data['nbUser'] = $this->user_model->count();
        $data['nbRestau'] = $this->restaurant_model->count();

        $this->load->view('templates/header');
        $this->load->view('admin/index', $data);
        $this->load->view('templates/footer');
    }

    public function addNews() {
        $data['title'] = 'Ajout';

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('title', 'El Titulo', 'required');
        $this->form_validation->set_rules('text', 'Le Superbe Texte', 'required');

        if($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view('news/create', $data);
            $this->load->view('templates/footer', $data);
        } else {
            $this->load->model('news_model');
            $this->news_model->set_news();
            $this->load->view('news/success');
        }
    }

    public function users() {
        $this->load->model('user_model');

        $data['membres'] = $this->user_model->getMember();

        $header['css'] = '<link href="'.base_url('assets/css/tableau.css').'" rel="stylesheet">';
        $footer['javascript'] = '<script type="text/javascript" src="'.base_url('assets/js/tableau.js').'"></script>';


        $this->load->view('templates/header', $header);
        $this->load->view('admin/users', $data);
        $this->load->view('templates/footer', $footer);
    }

    public function restaurants() {
        $this->load->model('restaurant_model');

        $data['restaurants'] = $this->restaurant_model->getRestaurant();

        $header['css'] = '<link href="'.base_url('assets/css/tableau.css').'" rel="stylesheet">';
        $footer['javascript'] = '<script type="text/javascript" src="'.base_url('assets/js/tableau.js').'"></script>';

        $this->load->view('templates/header', $header);
        $this->load->view('admin/restaurants', $data);
        $this->load->view('templates/footer', $footer);
    }

    public function modifierRestau($id){
        if(!$id) {
            redirect('config/restaurants');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('nom', 'Nom du restau', 'required|alpha_dash_space');
        $this->form_validation->set_rules('argent', 'Argent', 'required|integer');
        $this->form_validation->set_rules('capacite', 'Capacité', 'required|integer');
        $this->form_validation->set_rules('stock', 'Stock', 'required|integer');
        $this->form_validation->set_rules('notoriete', 'Notoriété', 'required|integer');
        $this->form_validation->set_rules('experience', 'Experience', 'required|integer');
        $this->form_validation->set_rules('etat', 'Etat', 'required|integer');
        $this->form_validation->set_rules('etatMax', 'Etat Max', 'required|integer');
        $this->form_validation->set_rules('open', 'Ouverture', 'required|integer');
        $this->form_validation->set_rules('openMax', 'Ouverture Max.', 'required|integer');

        if($this->form_validation->run()) {
            $data = array(
                'nom' => $this->input->post('nom'),
                'argent' => $this->input->post('argent'),
                'capacite' => $this->input->post('capacite'),
                'stock' => $this->input->post('stock'),
                'notoriete' => $this->input->post('notoriete'),
                'experience' => $this->input->post('experience'),
                'etat' => $this->input->post('etat'),
                'etatMax' => $this->input->post('etatMax'),
                'open' => $this->input->post('open'),
                'openMax' => $this->input->post('openMax'),
            );

            $this->restaurant_model->update($id, $data);
            $data['message'] = '<div class="alert alert-success">Modification réussie</div>';
        } else {
            $data['message'] = validation_errors('<div class="alert alert-danger">','</div>');
        }

        $data['restaurant'] = $this->restaurant_model->getRestaurant($id);

        $this->load->view('templates/header');
        $this->load->view('admin/restaurant/modifier', $data);
        $this->load->view('templates/footer');

    }

}
