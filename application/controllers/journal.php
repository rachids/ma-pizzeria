<?php

class Journal extends CI_Controller{
    private $idRestau = 0;
    public function __construct() {
        parent::__construct();
        $this->load->model('restaurant_model');
        $this->load->model('rapport_model');
        $this->load->library('pagination');

        if(!$this->session->userdata('logged_in')){
            $this->session->set_flashdata('flashmessage', 'Il faut être connecté pour voir cette page.');
            redirect('user/connexion','location');
        } elseif(!$this->session->userdata('idRestaurant')) {
            redirect('jeu/restaurant','location');
        } else {
            $restaurant = $this->restaurant_model->getRestaurantByMember($this->session->userdata('id'));
            $this->idRestau = $restaurant->id;
        }

    }

    public function index() {
        redirect('jeu/journal/consulter/prive', 'location');
    }

    public function pub($limit = 0) {
        $limit = (int) $limit;
        $config['per_page'] = 10;

        if($limit < 0) { $limit = 0; }

        $data['messages'] = $this->rapport_model->getAllPublicMessages($limit);
        $config['total_rows'] = $this->rapport_model->countAllMessages()->nb;

        $data['idRestau'] = $this->idRestau;

        $data['visibilite'] = 'public';

        #Pagination
        $config['base_url'] = site_url('jeu/journal/public/');
        $config['use_page_numbers'] = false;
        $config['uri_segment'] = 4;
        $config['full_tag_open'] = '<ul class="pagination">'; //balise ouvrante de la pagination
        $config['full_tag_close'] = '</ul>'; //balise fermante de la pagination
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['first_link'] = '<button class="btn btn-info"><i class="fa fa-chevron-circle-left"></i> Début</button>';
        $config['last_link'] = '<button class="btn btn-info"><i class="fa fa-chevron-circle-right"></i> Fin</button>';
        //$config['display_pages'] = FALSE;

        //Initialisation pagination
        $this->pagination->initialize($config);

        $data['paginationLink'] = $this->pagination->create_links();

        $this->load->view('templates/header');
        $this->load->view('jeu/journal/voir', $data);
        $this->load->view('templates/footer');
    }

    public function pri($limit = 0) {
        $limit = (int) $limit;
        $config['per_page'] = 10;

        if($limit < 0) { $limit = 0; }

        $data['messages'] = $this->rapport_model->getMessagesByPlayer($this->idRestau, $limit);
        $config['total_rows'] = $this->rapport_model->countAllMessages($this->idRestau)->nb;

        $data['idRestau'] = $this->idRestau;

        $data['visibilite'] = 'prive';

        #Pagination
        $config['base_url'] = site_url('jeu/journal/prive/');
        $config['use_page_numbers'] = false;
        $config['uri_segment'] = 4;
        $config['full_tag_open'] = '<ul class="pagination">'; //balise ouvrante de la pagination
        $config['full_tag_close'] = '</ul>'; //balise fermante de la pagination
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['first_link'] = '<button class="btn btn-info"><i class="fa fa-chevron-circle-left"></i> Début</button>';
        $config['last_link'] = '<button class="btn btn-info"><i class="fa fa-chevron-circle-right"></i> Fin</button>';
        //$config['display_pages'] = FALSE;

        //Initialisation pagination
        $this->pagination->initialize($config);

        $data['paginationLink'] = $this->pagination->create_links();

        $this->load->view('templates/header');
        $this->load->view('jeu/journal/voir', $data);
        $this->load->view('templates/footer');
    }

    public function vider(){
        $this->rapport_model->viderMsg();
        redirect('jeu/journal/prive', 'location');
    }
}